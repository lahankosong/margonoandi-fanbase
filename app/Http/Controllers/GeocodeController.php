<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Proxy + cache reverse-geocoding (Nominatim).
 * Tujuan: patuhi kebijakan Nominatim (User-Agent, maks 1 req/dtk, bukan geocoder
 * utama) dan kurangi panggilan drastis lewat cache per-koordinat — supaya IP
 * server tidak kena ban saat trafik naik.
 */
class GeocodeController extends Controller
{
    public function reverse(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);

        // Bulatkan ~3 desimal (~110 m): kunci cache & privasi lokasi
        $lat = round((float) $data['lat'], 3);
        $lon = round((float) $data['lon'], 3);
        $key = "geo:{$lat},{$lon}";

        // Cache hit → langsung balas, tidak menyentuh Nominatim
        $city = Cache::get($key);
        if ($city === null) {
            $city = $this->lookupNominatim($lat, $lon);
            // Hanya cache hasil sukses (jangan poison cache dgn null saat gagal/throttle)
            if ($city !== null && $city !== '') {
                Cache::put($key, $city, now()->addDays(30));
            }
        }

        return response()->json(['city' => $city ?: null]);
    }

    protected function lookupNominatim(float $lat, float $lon): ?string
    {
        try {
            // Serialkan akses + jaga jarak ≥1.1 dtk antar panggilan (kebijakan 1 req/dtk)
            $lock = Cache::lock('nominatim:lock', 8);
            if (!$lock->block(6)) return null;

            try {
                $last   = (float) Cache::get('nominatim:last', 0);
                $waitMs = 1100 - (int) ((microtime(true) - $last) * 1000);
                if ($waitMs > 0 && $waitMs < 1200) usleep($waitMs * 1000);

                $resp = Http::timeout(15)->withHeaders([
                    'User-Agent'      => 'MargonoandiFanbase/1.0 (+https://margonoandi.my.id)',
                    'Accept-Language' => 'id',
                ])->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'zoom'   => 12,
                    'lat'    => $lat,
                    'lon'    => $lon,
                ]);

                Cache::put('nominatim:last', microtime(true), now()->addMinutes(5));

                if (!$resp->successful()) return null;
                $a = $resp->json('address', []);
                foreach (['city', 'town', 'village', 'suburb', 'municipality', 'county', 'state'] as $k) {
                    if (!empty($a[$k])) return $a[$k];
                }
                return null;
            } finally {
                $lock->release();
            }
        } catch (\Throwable $e) {
            return null;
        }
    }
}
