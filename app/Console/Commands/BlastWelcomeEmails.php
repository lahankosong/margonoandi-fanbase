<?php

namespace App\Console\Commands;

use App\Helpers\WelcomeMailer;
use App\Models\User;
use Illuminate\Console\Command;

class BlastWelcomeEmails extends Command
{
    protected $signature = 'welcome:blast {--limit=20 : Maksimum email per jalan (jaga rate-limit SMTP)}';

    protected $description = 'Kirim email selamat datang ke user yang belum pernah dikirimi (backfill + retry yang gagal). Idempoten.';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $users = User::whereNull('welcome_email_sent_at')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('google_id', '!=', 'bot-margonoandi')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($users->isEmpty()) {
            $this->info('Tidak ada user yang perlu dikirimi. Semua sudah ter-handle.');
            return self::SUCCESS;
        }

        $sent = 0;
        $fail = 0;
        foreach ($users as $u) {
            try {
                WelcomeMailer::send($u);   // idempoten: hanya kirim + tandai bila belum
                $sent++;
                $this->line("  ✓ {$u->email}");
            } catch (\Throwable $e) {
                $fail++;
                report($e);
                $this->error("  ✗ {$u->email} — " . $e->getMessage());
            }
        }

        $remaining = User::whereNull('welcome_email_sent_at')
            ->whereNotNull('email')->where('email', '!=', '')
            ->where('google_id', '!=', 'bot-margonoandi')->count();

        $this->info("Selesai batch ini: {$sent} terkirim, {$fail} gagal. Sisa belum dikirim: {$remaining}.");

        // Exit code non-zero kalau semua gagal (mis. SMTP salah) — biar kelihatan di log cron.
        return ($sent === 0 && $fail > 0) ? self::FAILURE : self::SUCCESS;
    }
}
