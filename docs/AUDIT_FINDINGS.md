# Audit Findings — Status

**Sumber:** audit eksternal (Juni 2026) terhadap repo Margonoandi Fanbase.
**Diverifikasi langsung ke kode pada:** 2026-06-17.

Audit aslinya disusun tanpa membaca source file baris-per-baris (akses GitHub diblokir robots),
jadi sebagian klaim adalah inferensi. Tabel di bawah = **status hasil verifikasi langsung ke repo**.

---

## Temuan Keamanan

| # | Temuan | Status | Bukti / Perbaikan |
|---|--------|--------|-------------------|
| **C1** | `deploy.php`/`fixdb.php` (+`gitpatch.php`) pakai kunci publik `margono2026` | ✅ **Selesai** | Kunci kini dibaca dari `.env` `DEPLOY_KEY` (deny bila kosong); `margono2026` tak lagi di-hardcode. Kunci dirotasi ke string acak panjang. |
| **C2** | `.htaccess` root tanpa proteksi `.env`/dotfile | ✅ **Selesai** | `.htaccess` di-harden: `Options -Indexes` + `FilesMatch Require all denied` untuk `.env`, dotfile, `composer.*`, `*.md`, `vendor.zip`, dll + blok folder internal. |
| **C3** | `env('ADMIN_EMAILS')` di kode → runtuh saat `config:cache` | ✅ **Selesai** | `config/admin.php` (`config('admin.emails')`). Middleware `IsAdmin`, `AkuController`, & semua blade migrasi. Verifikasi: `env('ADMIN_EMAILS')` di app/blade = bersih. |
| **C4** | Kelas bug `$fillable`/`$casts` berulang, tanpa pencegah sistemik | ⏳ **Pending** | Banyak bug spesifik sudah diperbaiki, tapi belum ada test/Larastan sebagai pencegah. |
| **C5** | Nominatim dipanggil client-side per kirim pesan/post → risiko IP-ban | ✅ **Selesai** | `GeocodeController` `/geocode` (proxy + cache 30 hari per koordinat + User-Agent + spasi ≥1.1 dtk + throttle 60/min). `dia`/`kita` blade panggil `/geocode`, bukan nominatim.org langsung. |
| **C6** | Validasi upload media belum terverifikasi (risiko RCE/DoS) | ✅ **Selesai** | Upload chat divalidasi MIME + folder `chat_media` no-exec PHP. |

## Code Quality / Tech Debt

| # | Temuan | Status | Catatan |
|---|--------|--------|---------|
| Q8 | `routes.txt` (0 byte) di VCS | ✅ Dihapus | — |
| Q8 | `vendor.zip` di VCS | ⏳ **Dipertahankan (sengaja)** | Dipakai bootstrap `vendor/` ke shared hosting (tak bisa `composer install`). Trade-off: repo besar; bukan isu keamanan. |
| Q1-Q3 | Fat Blade views, tanpa Service/Policy, otorisasi tak terpusat | ⏳ Pending | Refactor kualitas; belum mendesak. |
| Q4-Q7, Q10 | Duplikasi like/comment, `@json()` workaround, form admin tak DRY, dua layout, type hints | ⏳ Pending | Kualitas, bukan lubang aktif. |
| Q9 | `SKILL.md` sebut "Laravel 11" padahal `composer.json` `^12` | ⏳ Pending | Kosmetik. |

## Missing Features vs PLANNING.md

| Fitur | Status | Catatan |
|-------|--------|---------|
| Bot System (P0 di PLANNING) | ❌ Belum ada | Sengaja di-deprioritisasi; energi ke ekosistem + pipeline AI (gambar Cloudinary, Pemotong Lagu, TTS) + keamanan. |
| Admin Dashboard Redesign | 🟡 Parsial | Tier-1 cleanup ada; lihat [ADMIN_CLEANUP_STRATEGY.md](ADMIN_CLEANUP_STRATEGY.md). |
| Visitor Tracking / Analytics | 🟡/❌ Parsial | `page_visits` + `TrackPageVisit` ada; metrik DAU/funnel & GA4 belum. |
| Ecosystem Fase 1 (musisi/follow/cari personil) | ✅ Live | — |

---

## Ringkasan

**Semua temuan keamanan kritis & medium (C1, C2, C3, C5, C6) + Q8 routes.txt = SELESAI.**
Sisa: **C4** (test layer), refactor kualitas (Q1-Q10), `vendor.zip` (dipertahankan sengaja),
dan fitur PLANNING (Bot System, dsb) yang bersifat roadmap — bukan lubang aktif.
