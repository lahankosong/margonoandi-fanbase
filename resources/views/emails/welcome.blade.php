<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>Selamat datang di Margonoandi</title>
</head>
<body style="margin:0; padding:0; background:#eef4f7; font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; color:#1f2d3a;">
@php $u = $appUrl; @endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef4f7; padding:24px 12px;">
<tr><td align="center">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(20,40,60,.08);">

    {{-- Header --}}
    <tr>
      <td style="background:linear-gradient(135deg,#38A8CC,#2186A8); padding:28px 32px;">
        <div style="font-size:11px; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.8); font-weight:700;">Margonoandi</div>
        <div style="font-size:21px; font-weight:700; color:#ffffff; margin-top:4px;">Ekosistem Musik Indie Indonesia 🎶</div>
      </td>
    </tr>

    {{-- Body --}}
    <tr><td style="padding:30px 32px 8px;">

      <p style="font-size:16px; margin:0 0 14px;">Halo <strong>{{ $firstName }}</strong>! 👋</p>

      <p style="font-size:14.5px; line-height:1.7; margin:0 0 16px; color:#33485a;">
        Selamat datang di keluarga <strong>Margonoandi</strong>. Senang banget kamu berani gabung lebih dulu.
        Kami sedang membangun satu tempat di mana musisi indie Indonesia bisa <em>tumbuh bareng</em> —
        saling kenal, cari personil, dapat panggung, dan belajar dari sesama. Bukan sekadar halaman,
        tapi sebuah ekosistem yang kita rawat pelan-pelan.
      </p>

      {{-- Fitur utama: Halaman Musisi --}}
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f9fb; border:1px solid #dcebf2; border-radius:12px; margin:8px 0 22px;">
        <tr><td style="padding:18px 20px;">
          <div style="font-size:11px; letter-spacing:.12em; text-transform:uppercase; color:#2186A8; font-weight:700; margin-bottom:12px;">Mulai dari Halaman Musisi</div>

          <div style="font-size:14px; line-height:1.5; margin-bottom:10px;">
            <strong>🎸 Direktori Musisi</strong><br>
            <span style="color:#5b7081; font-size:13px;">Kenalkan dirimu &amp; temukan musisi lain di sekitarmu.</span>
          </div>
          <div style="font-size:14px; line-height:1.5; margin-bottom:10px;">
            <strong>🎯 Cari Personil</strong><br>
            <span style="color:#5b7081; font-size:13px;">Butuh gitaris, basis, drummer, atau vokalis? Pasang lowongan band.</span>
          </div>
          <div style="font-size:14px; line-height:1.5; margin-bottom:4px;">
            <strong>📢 Papan Gig</strong><br>
            <span style="color:#5b7081; font-size:13px;">Peluang manggung, audisi, open mic, dan session player.</span>
          </div>
        </td></tr>
      </table>

      <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 24px;">
        <tr><td style="border-radius:26px; background:#38A8CC;">
          <a href="{{ $u }}/musisi" style="display:inline-block; padding:13px 30px; font-size:14.5px; font-weight:700; color:#ffffff; text-decoration:none;">Buka Halaman Musisi →</a>
        </td></tr>
      </table>

      {{-- Beta + ajakan share --}}
      <p style="font-size:14.5px; line-height:1.7; margin:0 0 16px; color:#33485a;">
        Jujur ya — aplikasi ini <strong>masih tahap beta</strong> dan untuk sekarang masih
        <strong>menumpang di web pribadi</strong> Margonoandi. Tapi kalau dukungan kalian besar,
        kita serius bangun rumah baru yang layak buat ekosistem ini. 🏠
      </p>
      <p style="font-size:14.5px; line-height:1.7; margin:0 0 14px; color:#33485a;">
        Bantu kami dong: <strong>bagikan ke teman-teman musisimu</strong>. Langkah besar ini dimulai
        dari kamu yang berani gabung lebih dulu. 🔥
      </p>

      <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 26px;">
        <tr><td style="border-radius:24px; border:1.5px solid #25D366;">
          <a href="https://wa.me/?text={{ rawurlencode('Coba gabung ekosistem musisi indie Indonesia ini, masih beta tapi keren: ' . $u) }}" style="display:inline-block; padding:10px 22px; font-size:13.5px; font-weight:700; color:#1aa34a; text-decoration:none;">📲 Bagikan via WhatsApp</a>
        </td></tr>
      </table>

      {{-- PWA install --}}
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fff8f2; border:1px solid #f6d9c2; border-radius:12px; margin:0 0 22px;">
        <tr><td style="padding:18px 20px;">
          <div style="font-size:14px; font-weight:700; color:#c2571f; margin-bottom:8px;">📱 Pasang sebagai Aplikasi (Android / PWA)</div>
          <p style="font-size:13px; line-height:1.7; margin:0 0 8px; color:#5b5048;">
            Web ini sebenarnya <strong>sudah berbentuk aplikasi Android</strong>. Kenapa belum kami taruh di Play Store?
            Dua alasan jujur: <strong>(1)</strong> proyek ini masih <strong>tahap beta</strong> — kami sedang menguji
            respon publik dulu sebelum serius; <strong>(2)</strong> untuk jadi developer di Play Store butuh
            <strong>biaya pendaftaran $25</strong>. Jadi untuk sekarang, kamu bisa <strong>instal langsung dari browser</strong>
            (fungsinya sama persis dengan aplikasi dari Play Store):
          </p>
          <ol style="font-size:13px; line-height:1.8; margin:0 0 8px; padding-left:18px; color:#5b5048;">
            <li>Buka situs ini di browser HP.</li>
            <li>Ketuk <strong>ikon titik tiga (⋮)</strong> atau ikon kecil di <strong>pojok kanan atas</strong>.</li>
            <li>Pilih <strong>“Tambahkan ke layar Utama”</strong> — akan muncul opsi <strong>Instal</strong> atau Pintasan.</li>
            <li>Pilih <strong>Instal</strong>.</li>
          </ol>
          <p style="font-size:13px; line-height:1.7; margin:0; color:#5b5048;">
            Dengan memilih <strong>Instal</strong>, kamu bisa menerima <strong>notifikasi</strong> saat ada komentar,
            chat, atau hal lain yang ditujukan untukmu. 🔔
          </p>
        </td></tr>
      </table>

      <p style="font-size:14px; line-height:1.7; margin:0 0 6px; color:#33485a;">
        Sampai jumpa di dalam, {{ $firstName }} 🎵<br>
        <strong>— Margonoandi</strong>
      </p>

    </td></tr>

    {{-- Footer --}}
    <tr>
      <td style="padding:18px 32px 26px; border-top:1px solid #eef2f5;">
        <p style="font-size:11.5px; line-height:1.6; color:#90a2b0; margin:0;">
          Kamu menerima email ini karena baru bergabung di Margonoandi dengan email ini.<br>
          <a href="{{ $u }}" style="color:#2186A8; text-decoration:none;">{{ str_replace(['https://','http://'], '', $u) }}</a>
        </p>
      </td>
    </tr>

  </table>

</td></tr>
</table>
</body>
</html>
