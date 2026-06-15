<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;

class TrackPageVisit
{
    public function handle(Request $request, Closure $next, string $page): mixed
    {
        $response = $next($request);

        // Hanya catat GET request yang sukses (200), skip AJAX/API
        if ($request->isMethod('GET') && !$request->ajax()
            && $response->getStatusCode() === 200) {
            try {
                $sessionKey = 'pv_tracked_' . $page;

                // Satu kali per sesi per jenis halaman
                if (!session()->has($sessionKey)) {
                    session([$sessionKey => true]);
                    PageVisit::create([
                        'page'       => $page,
                        'session_id' => session()->getId(),
                        'ip'         => $request->ip(),
                        'user_id'    => auth()->id(),
                        'created_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Jangan biarkan kegagalan tracking merusak halaman
            }
        }

        return $response;
    }
}
