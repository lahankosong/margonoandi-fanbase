<?php

namespace App\Helpers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class WelcomeMailer
{
    /**
     * Kirim email sambutan ke user lalu tandai welcome_email_sent_at.
     * Idempoten: lewati jika email kosong / sudah pernah dikirim.
     * Return true bila benar-benar terkirim.
     */
    public static function send(User $user): bool
    {
        if (empty($user->email) || $user->welcome_email_sent_at) {
            return false;
        }

        Mail::to($user->email)->send(new WelcomeEmail($user));

        // saveQuietly: jangan picu event model (mis. observer) hanya untuk penanda ini.
        $user->forceFill(['welcome_email_sent_at' => now()])->saveQuietly();

        return true;
    }

    /**
     * Versi untuk alur login: kirim SETELAH respons (afterResponse) agar tak menambah jeda,
     * dan bungkus error supaya gagal kirim tidak pernah memutus login.
     */
    public static function sendAfterResponse(User $user): void
    {
        if (empty($user->email) || $user->welcome_email_sent_at) {
            return;
        }

        dispatch(function () use ($user) {
            try {
                self::send($user);
            } catch (\Throwable $e) {
                report($e);
            }
        })->afterResponse();
    }
}
