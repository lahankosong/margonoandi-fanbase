# Admin Dashboard Cleanup Strategy

**Project:** Margonoandi Music Content Production
**Version:** 2.0 (Manual workflow, roadmap untuk automasi)
**Updated:** Juni 2026
**Status:** Planning phase (belum di-implement)

> Dokumen ini dipindahkan dari sesi Claude.ai web ke repo (`docs/`) sebagai referensi.
> Beberapa route yang disebut (mis. `admin.production`, `admin.video-builder`) **belum ada** —
> ini rencana, bukan keadaan kode saat ini.

---

## Daftar Isi

1. Tujuan Akhir
2. Masalah Sekarang
3. Solusi: Workflow Terstruktur
4. Perubahan Naming
5. Dashboard Produksi (Halaman Baru)
6. Video Builder (Halaman Baru)
7. Data Model Updates
8. Struktur Navigasi
9. Roadmap Implementasi
10. Quick Wins (Mulai dari sini)

---

## Tujuan Akhir

### Vision

Satu workflow yang jelas dari **lagu -> konten -> video siap posting**. Sekarang manual (step-by-step), nanti otomatis.

### Input User

1. **Pilih lagu** dari library
2. **Pilih tipe video:**
   - 📱 **Short Video (9:16):** 15-60 detik, backsound lagu, 1 image + narasi singkat
   - 🎬 **Long Form (3-5 menit):** Narasi panjang, multiple visual scenes, backsound lagu
   - 🌐 **Tema Umum:** Narasi TTS, backsound lagu, visual scenes (bisa berbeda dengan lagu, cth: artikel tentang mindfulness)
3. **(Opsi) Potong bagian lagu tertentu** untuk short video (mis: Verse 1 saja, 0:12-0:45)
4. **(Opsi) Tambah text/lirik custom** untuk narasi

### Output Akhir

```
Video siap posting (MP4)
- Format: 9:16 (short) / 16:9 (long) / custom
- Duration: 15-60 detik (short) / 3-5 menit (long)
- Audio:
  - Lagu asli (backsound)
  - (Opsi) TTS narasi (fade in 50-70%)
- Visual:
  - Still image / generated image (short)
  - Multiple scenes (long)
- Ready to upload: TikTok, Instagram, YouTube, Discord
```

---

## Masalah Sekarang

### 1. Fragmented Workflow (Tersebar)

```
User harus tab-hopping:
Dashboard -> AI Agent (generate) -> Audio-Cut (potong) -> Calendar (jadwal) -> ??? (mana video-nya?)
```

**Hasil:** User bingung "saya sudah jalanin semua langkah, tapi video-nya di mana?"

### 2. Data Tidak Tersimpan Proper

| Phase | Storage | Problem |
|-------|---------|---------|
| AI Agent generate | JavaScript `SAVED` state (frontend) | Hilang saat refresh, tidak accessible dari halaman lain |
| Audio-Cut clips | IndexedDB (browser only) | Tidak terhubung ke song ID atau calendar item |
| Calendar schedule | Database ✓ | Baik, tapi tidak tahu origin-nya (dari AI atau manual?) |

### 3. Multiple Entry Points -> Confusion

- **Calendar:** Ada 2 cara tambah konten: dari AI Agent (POST `/admin/ai-agent/schedule`) dan form manual di Calendar page.
- **Hasil:** User tidak tahu "kalau generate di AI Agent, apakah otomatis masuk Calendar?" Jawaban: ya, tapi data di JS saja, tidak ke DB.

### 4. Audio-Cut Terisolasi

Studio Audio bisa potong lagu dan simpan clips, tapi:
- Tidak terhubung ke AI Agent (clips tak bisa dipilih saat generate)
- Tidak terhubung ke Video Builder
- Clips hanya di IndexedDB, tidak bisa diakses dari device lain

### 5. Tidak Ada "Video Builder"

**The missing piece:** halaman yang mengombinasikan hasil AI generation (narasi, prompt) + audio clips (lagu/TTS) + generated images menjadi satu video preview.

Sekarang user harus manual: download narasi -> potong audio -> generate image (situs terpisah) -> edit di CapCut/Adobe -> baru upload.

### 6. Settings Tersebar

- API keys & provider config -> AI Settings (`admin/ai-settings`)
- Artist bio, tagline, social links -> Website Settings (`admin/settings`)
- Belum ada "project defaults" (default lagu? default platform?)

---

## Solusi: Workflow Terstruktur

### Prinsip

1. **Clarity:** User selalu tahu di mana dia & langkah selanjutnya
2. **Data Persistence:** Semua hasil disimpan ke DB (bukan JS saja)
3. **Integration:** Setiap fase terhubung dengan data fase sebelumnya
4. **Manual Now, Automatic Later:** Architected untuk automation, sekarang manual dulu

### Workflow Ideal (5 Fase)

**Fase 1 — LAGU**
Pilih 1 lagu dari library; review judul, durasi, key, tempo, lirik. Output: Song record (DB).

**Fase 2 — CONTENT GENERATOR**
Input: Song ID + provider config. AI generate niche -> topics -> narasi -> prompts.
Output (`ai_generations`): 5 short topics (label + narasi + image prompt), 1 long form (narasi + 5 scene prompts), 3 tema umum (angle + narasi + prompts).

**Fase 3 — STUDIO AUDIO**
Input: song audio file. Potong bagian yang dipakai. Output (`audio_clips`): clip linked ke `song_id`, `duration_start`/`duration_end`, url storage.

**Fase 4 — VIDEO BUILDER ⭐ (HALAMAN BARU)**
Combine hasil Fase 2 & 3 jadi video:
1. Pilih tipe (short/long/umum)
2. Pilih konten dari Content Generator
3. Pilih audio (dari Studio Audio atau full lagu)
4. Generate/upload visual
5. Review + jadwalkan
Output: CalendarPlan + `video_metadata` JSON.

**Fase 5 — JADWAL & POST**
Status: Rencana -> Proses -> Selesai. Manual: export + upload. Later: auto-publish via API.

---

## Perubahan Naming

| Sekarang | Sebaiknya | Path (baru) | Alasan |
|----------|-----------|-------------|--------|
| Dashboard | **Production Dashboard** | `/admin/production` | Hub utama, bukan summary |
| AI Agent | **Content Generator** | `/admin/content-generator` | Jelas: generate niche, topik, narasi, prompt |
| Audio-Cut | **Studio Audio** | `/admin/studio-audio` | Workspace audio production |
| AI Settings | **Production Config** | `/admin/production-config` | Semua AI keys & provider di satu tempat |
| Settings | **Website Settings** | `/admin/website-settings` | Distinct dari production config |
| Calendar | **Jadwal Posting** | `/admin/jadwal-posting` | Lebih jelas scope-nya |
| Promo | **Bank Caption** | `/admin/caption-bank` | Templates, bukan "promo" |
| — | **Video Builder** ⭐ | `/admin/video-builder` | HALAMAN BARU: combine asset jadi video |

---

## Dashboard Produksi (Halaman Baru)

**Purpose:** single entry point untuk seluruh workflow. Buka `/admin` -> langsung tahu di mana.

### Layout: 2x2 Grid

- **Card 1 — 🎵 Lagu:** dropdown pilih lagu; show judul/durasi/era; tombol Edit & + Buat.
- **Card 2 — ✨ Content Generator:** status badge (siap/proses/draft ready); summary (5 topik, 1 long, 3 tema); tombol Edit & Generate.
- **Card 3 — 🎙️ Studio Audio:** list 3 clips terakhir + durasi; tombol + Potong.
- **Card 4 — 📅 Jadwal Posting:** count post minggu ini; 3 post terdekat + status; tombol Lihat semua.
- **Hint section:** tunjukkan langkah yang belum dijalankan + link next step.
- **Quick links:** Production Config, Website Settings, Caption Bank.

---

## Video Builder (Halaman Baru)

**Purpose:** the missing link — combine semua asset jadi satu video. Form 5-step:

- **Step 1 — Tipe Video:** radio Short (9:16) / Long Form / Tema Umum.
- **Step 2 — Pilih Konten:** dari Content Generator (topik mana?); opsi `[x] Pakai TTS` + dropdown voice (Kore, Aoede, Leda, ...).
- **Step 3 — Audio:** pilih clip dari Studio Audio (Verse/Chorus/Outro/Full) atau upload custom (.mp3/.wav/.m4a, max 50MB); audio preview.
- **Step 4 — Visual:** generate baru (Pollinations) / pilih dari image library / upload custom (.jpg/.png, max 5MB); preview.
- **Step 5 — Review & Jadwalkan:** summary lengkap + estimate durasi/size; date/time picker; platform checkbox; tombol Schedule.

### Output (POST ke `calendar_plans` + `video_metadata` JSON)

```php
{
  song_id: 42,
  plan_date: '2026-06-20',
  content_type: 'short',
  platforms: 'TikTok,Instagram',
  status: 'rencana',
  source_type: 'video_builder',
  video_metadata: {
    type: 'short',
    audio_clip_id: 5,
    audio_duration: 33,
    narration_type: 'tts',
    narration_voice: 'Kore',
    narration_text: 'Padamkan sejenak bara egomu...',
    image_source: 'generated',
    image_prompt: 'A solitary figure in dark room...',
    image_url: 'https://pollinations.ai/...',
    duration_estimate: 35,
    size_estimate: '2.5 MB'
  }
}
```

---

## Data Model Updates

### 1. `calendar_plans` (tambah kolom)

```sql
ALTER TABLE calendar_plans ADD COLUMN (
  source_type VARCHAR(20) DEFAULT 'manual',  -- 'manual' / 'video_builder'
  ai_generation_id BIGINT UNSIGNED NULL,
  FOREIGN KEY (ai_generation_id) REFERENCES ai_generations(id)
);
ALTER TABLE calendar_plans MODIFY COLUMN notes JSON;
ALTER TABLE calendar_plans CHANGE notes video_metadata JSON NULL;
```

### 2. `audio_clips` (tabel baru)

```sql
CREATE TABLE audio_clips (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  song_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(255),               -- "Verse 1 Hook", "Chorus", dll
  duration_start DECIMAL(8,2),     -- detik
  duration_end DECIMAL(8,2),       -- detik
  duration_seconds DECIMAL(8,2),   -- auto-calculated
  audio_url VARCHAR(500),          -- storage path / URL
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (song_id) REFERENCES songs(id) ON DELETE CASCADE
);
```

### 3. `ai_generations` (sudah ada sebagian — pastikan kolom lengkap)

```sql
CREATE TABLE ai_generations (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  song_id BIGINT UNSIGNED NOT NULL,
  ai_provider_id BIGINT UNSIGNED,
  mode VARCHAR(20),                -- 'short','long','umum','all'
  niche JSON, topics JSON, long_form JSON, umum JSON,
  style_applied JSON,
  provider_name VARCHAR(100),
  created_at TIMESTAMP, updated_at TIMESTAMP,
  FOREIGN KEY (song_id) REFERENCES songs(id) ON DELETE CASCADE
);
```

> Catatan keadaan kode saat ini: `ai_generations` & `ai_images` sudah ada. Audio clips
> saat ini di IndexedDB (`mafAudioClips`), belum ada tabel `audio_clips` server-side.

### Routes (rename + new, dengan backward-compat redirect)

```php
Route::middleware('admin')->prefix('admin')->group(function () {
  Route::get('/production', 'ProductionDashboardController@show')->name('admin.production-dashboard');
  Route::get('/content-generator', 'ContentGeneratorController@show')->name('admin.content-generator');
  Route::get('/studio-audio', 'AudioStudioController@show')->name('admin.studio-audio');
  Route::get('/production-config', 'ProductionConfigController@show')->name('admin.production-config');
  Route::get('/website-settings', 'WebsiteSettingsController@show')->name('admin.website-settings');
  Route::get('/jadwal-posting', 'ScheduleController@index')->name('admin.jadwal-posting');
  Route::get('/caption-bank', 'CaptionBankController@show')->name('admin.caption-bank');

  // NEW
  Route::get('/video-builder', 'VideoBuilderController@show')->name('admin.video-builder');
  Route::post('/video-builder', 'VideoBuilderController@store')->name('admin.video-builder.store');

  Route::post('/content-generator/generate', 'ContentGeneratorController@generate')->name('admin.content-generator.generate');
  Route::post('/studio-audio/clip', 'AudioStudioController@createClip')->name('admin.studio-audio.clip');
  Route::delete('/studio-audio/clip/{id}', 'AudioStudioController@deleteClip')->name('admin.studio-audio.clip.delete');

  // Backward compat (deprecated)
  Route::redirect('/ai-agent', '/content-generator');
  Route::redirect('/audio-cut', '/studio-audio');
  Route::redirect('/ai-settings', '/production-config');
  Route::redirect('/settings', '/website-settings');
  Route::redirect('/promo', '/caption-bank');
  Route::redirect('/calendar', '/jadwal-posting');
});
```

---

## Struktur Navigasi

**Sidebar (ideal):** Dashboard | PRODUCTION (Song Library, Content Generator, Studio Audio, Video Builder ⭐, Jadwal Posting) | CONTENT (Caption Bank) | SETTINGS (Production Config, Website Settings) | Help.

**Top Nav (lebih mudah jangka pendek):** Dashboard | Song Library | Content Generator | Studio Audio | Video Builder ⭐ | Jadwal Posting | Prod Config | Website.

**Breadcrumbs (semua halaman):** mis. `Dashboard > Content Generator > Draft [edit]`.

---

## Roadmap Implementasi (4 minggu)

### Minggu 1 — Rename + Backend Setup (no UI changes) — ±4 jam
- Rename routes (`ai-agent` -> `content-generator`, dst) + redirect compat
- Migrations: `ai_generations`, `audio_clips`; update `calendar_plans` (source_type, ai_generation_id, video_metadata)
- Models: `AIGeneration`, `AudioClip`; relasi di `CalendarPlan`

### Minggu 2 — UI Cleanup + Dashboard — ±6 jam
- Rename view files (ai-agent -> content-generator, dst)
- `ProductionDashboardController` + view 4-card grid
- Breadcrumbs component + pasang di tiap admin page

### Minggu 3 — Content Generator + Studio Audio Persistence — ±8 jam
- `generate()` simpan hasil ke `ai_generations` (bukan cuma JS), return `ai_generation_id`
- View load dari DB + timestamp "Generated by ..."
- Audio clip CREATE/DELETE ke `audio_clips` (bukan cuma IndexedDB), associate `song_id`

### Minggu 4 — Video Builder (the big one) — ±12 jam
- `VideoBuilderController` (5-step form logic) + view
- `CalendarPlanController@store()` terima `video_metadata`, set `source_type='video_builder'`, link `ai_generation_id`

### Minimal MVP (Quick Win — minggu 1 saja, 2-3 jam)
1. Dashboard Produksi (4 cards, data hardcoded, link ke halaman existing)
2. Rename routes (backward-compat redirect)
3. Breadcrumbs (component di tiap page)

Memberi: clarity langsung, tanpa perubahan fungsional.

---

## Quick Wins (Mulai dari sini)

### Task 1 — Production Dashboard (±1.5 jam)

`app/Http/Controllers/ProductionDashboardController.php`:

```php
<?php
namespace App\Http\Controllers;
use App\Models\Song;

class ProductionDashboardController extends Controller
{
    public function show()
    {
        $songs = Song::where('is_active', 1)->get();
        return view('admin.production-dashboard', [
            'songs' => $songs,
            'totalSongs' => $songs->count(),
        ]);
    }
}
```

`resources/views/admin/production-dashboard.blade.php`: `@extends('layouts.admin')` + 2x2 grid 4 card (Lagu / Content Generator / Studio Audio / Jadwal+Config) + hint workflow. (Lihat snippet lengkap di histori — pakai CSS var: `--bg-2`, `--border`, `--text`, `--accent`, dll. yang sudah ada di layout admin.)

`routes/web.php`:
```php
Route::get('/admin/production', [\App\Http\Controllers\ProductionDashboardController::class, 'show'])
  ->name('admin.production');
```

### Task 2 — Breadcrumbs Component (±1 jam)

`resources/views/components/breadcrumbs.blade.php`:

```blade
@props(['items' => []])
@if (count($items) > 0)
<div style="display:flex;gap:.5rem;align-items:center;margin-bottom:1.5rem;font-size:.9rem;">
  @foreach ($items as $key => $item)
    @if (is_array($item))
      @if ($key === array_key_last($items))
        <span style="color:var(--text-2);">{{ $item['label'] }}</span>
      @else
        <a href="{{ $item['url'] }}" style="color:var(--text-3);text-decoration:none;">{{ $item['label'] }}</a>
        <span style="color:var(--text-4);">/</span>
      @endif
    @else
      <span style="color:var(--text-2);">{{ $item }}</span>
    @endif
  @endforeach
</div>
@endif
```

Pakai: `<x-breadcrumbs :items="[['label'=>'Dashboard','url'=>route('admin.production')], 'Content Generator']" />`

### Task 3 — Update Sidebar/Nav (±1 jam)

Tambah link "Dashboard Produksi" di `resources/views/layouts/admin.blade.php`.

---

## Next Steps

1. Konsultasi Claude (VSCode): "dari quick wins, mulai dari mana?", "struktur migration oke?", "logic Video Builder?"
2. Implement Quick Wins (minggu 1): Dashboard Produksi, rename routes, breadcrumbs
3. Test navigasi clear
4. Plan Video Builder

## Notes

- Dokumen **planning phase** — belum implementasi
- Backward compat: old routes redirect ke new routes
- Data migration: `SAVED` state JS -> `ai_generations` table; clips IndexedDB -> `audio_clips` table
- Future-proof: architected untuk automation, sekarang manual dulu

**Maintained by:** Andi (Rakhman) · **Status:** Ready for Claude VSCode consultation
