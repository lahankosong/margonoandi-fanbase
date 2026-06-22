<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect('/aku');
        }
        $songs = Song::where('is_active', true)
                     ->orderBy('track_number')
                     ->get();

        $featuredSong = $songs->where('youtube_id', 'TG8oAcVRnzA')->first()
                     ?? $songs->first();

        $ctaSongs = Song::where('featured', true)
                        ->where('is_active', true)
                        ->take(3)
                        ->get();

        $settings = SiteSetting::all()->keyBy('key')->map(fn($s) => $s->value);

        $artistName = $settings['artist_name'] ?? 'Margonoandi';
        $heroDesc   = \Illuminate\Support\Str::limit(strip_tags($settings['hero_story'] ?? ''), 160)
                    ?: 'Dengarkan lagu Margonoandi, belajar chord & tuner, dan gabung komunitas musisi Indonesia.';
        $sameAs = array_values(array_filter([
            $settings['spotify_url'] ?? null,
            $settings['youtube_url'] ?? null,
            $settings['apple_music_url'] ?? null,
        ]));

        $seo = [
            'title'       => 'Margonoandi — Lagu, Chord & Komunitas Musik Indonesia',
            'description' => $heroDesc,
            'image'       => asset('images/Margonoandi.jpeg'),
            'url'         => url('/'),
            'type'        => 'music.musician',
            'schema'      => [
                '@context'    => 'https://schema.org',
                '@type'       => 'MusicGroup',
                'name'        => $artistName,
                'url'         => url('/'),
                'image'       => asset('images/Margonoandi.jpeg'),
                'genre'       => ['Indie', 'Pop Indonesia'],
                'description' => \Illuminate\Support\Str::limit(strip_tags($settings['hero_story'] ?? ''), 250),
                'sameAs'      => $sameAs,
            ],
        ];

        return view('home', compact('songs', 'featuredSong', 'ctaSongs', 'settings', 'seo'));
    }

    /** sitemap.xml dinamis: homepage + semua lagu aktif. */
    public function sitemap()
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= '  <url><loc>' . htmlspecialchars(url('/')) . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";

        try {
            $songs = Song::where('is_active', true)
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->get(['slug', 'updated_at']);
            foreach ($songs as $s) {
                if (empty($s->slug)) continue;
                try { $loc = route('song.show', $s->slug); } catch (\Throwable $e) { continue; }
                $xml .= '  <url><loc>' . htmlspecialchars($loc) . '</loc>';
                if ($s->updated_at) $xml .= '<lastmod>' . $s->updated_at->toAtomString() . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
            }
        } catch (\Throwable $e) {}

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}