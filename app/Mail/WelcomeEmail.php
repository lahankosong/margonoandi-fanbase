<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        // Hormati MAIL_FROM_ADDRESS dari .env bila diset; kalau belum / masih default Laravel,
        // pakai alamat branded sebagai fallback.
        $fromAddr = config('mail.from.address');
        if (! $fromAddr || $fromAddr === 'hello@example.com') {
            $fromAddr = 'no-reply@margonoandi.my.id';
        }
        $fromName = config('mail.from.name') ?: 'Margonoandi';

        return new Envelope(
            from:    new Address($fromAddr, $fromName),
            subject: 'Selamat datang di Margonoandi 🎶 — ayo bangun ekosistem musik indie Indonesia',
        );
    }

    public function content(): Content
    {
        $first = trim(Str::before($this->user->name ?? 'Kawan', ' ')) ?: 'Kawan';

        return new Content(
            view: 'emails.welcome',
            with: [
                'firstName' => $first,
                'appUrl'    => rtrim(config('app.url'), '/'),
            ],
        );
    }
}
