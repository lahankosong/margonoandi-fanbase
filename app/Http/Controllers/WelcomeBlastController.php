<?php

namespace App\Http\Controllers;

use App\Helpers\WelcomeMailer;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Broadcast email selamat datang ke user LAMA (yang belum pernah dikirimi).
 * Terproteksi DEPLOY_KEY (.env). Idempoten via kolom welcome_email_sent_at,
 * dikirim per-batch agar aman dari timeout & batas rate SMTP shared hosting.
 *
 * Akses: /dev/blast-welcome?key=<DEPLOY_KEY>            -> halaman konfirmasi
 *        /dev/blast-welcome?key=<DEPLOY_KEY>&go=1        -> kirim 1 batch (auto-lanjut)
 */
class WelcomeBlastController extends Controller
{
    private const BATCH = 15;       // email per request
    private const REFRESH_SECS = 12; // jeda antar batch (hormati rate limit SMTP)

    public function __invoke(Request $request)
    {
        if (! hash_equals($this->deployKey(), (string) $request->query('key', '')) || $this->deployKey() === '') {
            abort(403, 'DEPLOY_KEY salah atau belum diset di .env');
        }

        $base = User::whereNull('welcome_email_sent_at')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('google_id', '!=', 'bot-margonoandi');

        $pending = (clone $base)->count();
        $key     = urlencode($request->query('key'));

        // Halaman konfirmasi (belum kirim)
        if (! $request->boolean('go')) {
            return $this->page(
                "<h2>Broadcast Email Selamat Datang</h2>
                 <p class='info'>User yang BELUM pernah dikirimi: <b>{$pending}</b></p>
                 <p class='info'>Akan dikirim " . self::BATCH . " email per batch, otomatis lanjut tiap " . self::REFRESH_SECS . " detik.</p>
                 " . ($pending > 0
                    ? "<a class='btn' href='?key={$key}&go=1'>▶ Mulai Kirim</a>"
                    : "<p class='ok'>✓ Semua user sudah pernah dikirimi. Tidak ada yang perlu dikirim.</p>")
            );
        }

        // Kirim 1 batch
        $users = (clone $base)->orderBy('id')->take(self::BATCH)->get();
        $sent = 0; $fail = 0; $rows = '';
        foreach ($users as $u) {
            try {
                WelcomeMailer::send($u);
                $sent++;
                $rows .= "<pre class='ok'>✓ " . htmlspecialchars($u->email) . "</pre>";
            } catch (\Throwable $e) {
                $fail++;
                report($e);
                $rows .= "<pre class='err'>✗ " . htmlspecialchars($u->email) . " — " . htmlspecialchars($e->getMessage()) . "</pre>";
            }
        }

        $remaining = max(0, $pending - $sent);
        $meta = '';
        $next = '';
        if ($remaining > 0 && $sent > 0) {
            $meta = "<meta http-equiv='refresh' content='" . self::REFRESH_SECS . ";url=?key={$key}&go=1'>";
            $next = "<p class='info'>Lanjut otomatis dalam " . self::REFRESH_SECS . " detik… (tutup tab ini untuk berhenti)</p>
                     <a class='btn' href='?key={$key}&go=1'>Lanjut sekarang →</a>";
        } elseif ($sent === 0 && $fail > 0) {
            $next = "<p class='err'>Semua gagal kirim — cek konfigurasi MAIL_* di .env. Auto-lanjut dihentikan.</p>";
        } else {
            $next = "<p class='ok'>✓ Selesai! Semua user sudah dikirimi email selamat datang.</p>";
        }

        return $this->page(
            "<h2>Mengirim… batch ini: ✓ {$sent} terkirim, ✗ {$fail} gagal</h2>
             <p class='info'>Sisa belum dikirim: <b>{$remaining}</b></p>
             {$rows}
             {$next}",
            $meta
        );
    }

    /** Baca DEPLOY_KEY langsung dari .env (robust walau config di-cache). */
    private function deployKey(): string
    {
        $envFile = base_path('.env');
        if (is_file($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;
                if (strncmp($line, 'DEPLOY_KEY=', 11) === 0) {
                    return trim(substr($line, 11), " \t\"'");
                }
            }
        }
        return (string) env('DEPLOY_KEY', '');
    }

    private function page(string $body, string $meta = '')
    {
        $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>{$meta}
        <title>Blast Welcome</title><style>
        body{font-family:monospace;background:#0b1520;color:#e8f4fa;padding:2rem;max-width:760px;margin:0 auto}
        h2{color:#38A8CC;font-size:16px}a.btn{display:inline-block;margin-top:1rem;background:#38A8CC;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:600}
        pre{background:#0f1e2e;border:1px solid rgba(56,168,204,.2);padding:.5rem .7rem;border-radius:6px;margin:3px 0;font-size:12px;white-space:pre-wrap}
        .ok{color:#4ade80}.err{color:#f87171}.info{color:#7A9DB0}</style></head><body>{$body}</body></html>";
        return response($html);
    }
}
