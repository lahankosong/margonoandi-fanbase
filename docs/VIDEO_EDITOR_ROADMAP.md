# Video Editor — Roadmap

**Owner:** Andi (Rakhman) · **Updated:** 2026-06-18 · **Status:** v1 (Video Builder) live, berkembang bertahap.

Ringkasan dari dua dokumen visi (VIDEO_EDITOR_ROADMAP.md & VIDEO_EDITOR_TEMPLATE_SYSTEM.md asli).
Tujuan: dari **Video Builder v1** (1 frame statis + audio + caption) → **editor video profesional**
(multi-clip timeline, efek, teks animasi, audio mixing, template) — semua di browser (ffmpeg.wasm + Canvas + Web Audio).

## Yang SUDAH ada (Video Builder v1 — `/admin/video-builder`)
- Pilih gambar (Cloudinary / upload) + audio (IndexedDB: potongan lagu & narasi TTS / upload)
- Caption: narasi (build-up) + gong (pamungkas), **di-bake ke frame** (anti overlay silent-fail), timing via concat
- Template caption (Impact/Bold Box/Neon/Tulisan tangan), posisi narasi (atas/tengah/bawah), animasi gong (fade+zoom)
- Render MP4 (libx264 → fallback mpeg4), simpan video ke library (IndexedDB `mafVideos`)
- Mixing narasi + musik **sudah berhasil** (kualitas lagu tak turun)

## Roadmap bertahap (disederhanakan dari dokumen, realistis untuk shared hosting + ffmpeg.wasm)

| Fase | Fokus | Status |
|------|-------|--------|
| **A — Editor teks bebas** | Customisasi teks: font (Google Fonts), ukuran, warna, **posisi drag**, template sebagai preset | 🔜 sedang dikerjakan |
| **B — Multi-scene** | Beberapa gambar berganti sepanjang video (concat N frame), durasi per scene | pending |
| **C — Efek & transisi** | brightness/contrast/saturation (ffmpeg `eq`), fade antar scene | pending |
| **D — Audio mixing lanjut** | volume per track, fade in/out, EQ ringan | sebagian (mix narasi+musik sudah) |
| **E — Template proyek** | Simpan/muat blueprint video (JSON) di DB, galeri template, "Use Template" | pending |
| **F — Export preset** | preset TikTok/IG/YouTube (resolusi/fps/bitrate) | pending |

## Prinsip
- **Semua proses di browser** (server ringan) — render ffmpeg.wasm, teks via Canvas (bake), cache IndexedDB.
- Caption **di-bake ke frame** (bukan filter overlay) karena `drawtext`/`overlay+enable` sering silent-fail di core wasm.
- Timing antar segmen pakai **concat** (filter inti, andal); animasi via beberapa frame baked (alpha/scale).
- Hindari fitur yang butuh job server berat (shared cPanel). Long render → split/short dulu.

## Catatan teknis dari dokumen (untuk fase lanjut)
- Template = blueprint JSON (tracks: video/text/audio, clips, position, style, animations) → tabel `video_templates` + `video_projects` (timeline JSON) bila masuk fase E.
- Font customization: kategori sans/serif/display/mono dari Google Fonts.
- Export preset: 9:16 1080x1920 (TikTok/IG/Shorts), 16:9 1920x1080 (YT), 1:1 (FB).
- Tantangan diakui: performa timeline besar, memori efek, render panjang (split), kuota IndexedDB, lintas-browser.

> Keputusan terbuka (dari dokumen): template shareable antar-user? cloud storage? kolaborasi multi-user? watermark? — ditunda sampai fitur inti stabil.
