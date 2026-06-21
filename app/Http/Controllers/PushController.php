<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Models\AppNotification;
use App\Helpers\WebPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    /** Kirim notifikasi tes ke diri sendiri — untuk diagnosa pipeline push. */
    public function test()
    {
        if (!WebPush::enabled()) {
            return response()->json(['ok' => false, 'msg' => 'VAPID belum diset di server (.env)']);
        }
        $count = PushSubscription::where('user_id', Auth::id())->count();
        if ($count === 0) {
            return response()->json(['ok' => false, 'msg' => 'Belum berlangganan. Klik Aktifkan & izinkan dulu.']);
        }
        try {
            AppNotification::create([
                'user_id'      => Auth::id(),
                'from_user_id' => Auth::id(),
                'type'         => 'message',
                'title'        => 'Tes Notifikasi',
                'body'         => 'Kalau ini muncul di tray HP, notifikasi sistem sudah jalan! 🎉',
                'url'          => url('/dia'),
                'icon'         => '🔔',
            ]);
        } catch (\Throwable $e) {}

        try { WebPush::sendToUser(Auth::id()); } catch (\Throwable $e) {}

        return response()->json(['ok' => true, 'subs' => $count]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string|max:500',
            'p256dh'   => 'nullable|string|max:255',
            'auth'     => 'nullable|string|max:255',
        ]);

        try {
            PushSubscription::updateOrCreate(
                ['endpoint' => $data['endpoint']],
                [
                    'user_id' => Auth::id(),
                    'p256dh'  => $data['p256dh'] ?? null,
                    'auth'    => $data['auth'] ?? null,
                ]
            );
        } catch (\Throwable $e) {
            return response()->json(['ok' => false], 200);
        }

        return response()->json(['ok' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $endpoint = (string) $request->input('endpoint');
        if ($endpoint !== '') {
            try {
                PushSubscription::where('endpoint', $endpoint)
                    ->where('user_id', Auth::id())->delete();
            } catch (\Throwable $e) {}
        }
        return response()->json(['ok' => true]);
    }
}
