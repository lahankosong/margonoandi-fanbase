@extends('layouts.app')

@push('styles')
<style>
    :root { --ac:#38A8CC; --ac-dk:#0ea5e9; --ac-lt:rgba(56,168,204,.12); }

    .roy-page { padding:1.75rem 1rem 5rem; }
    .roy-back { display:inline-flex;align-items:center;gap:5px;font-size:13px;color:var(--text-3);text-decoration:none;margin-bottom:1.25rem; }
    .roy-back:hover { color:var(--text); }

    .roy-hero { text-align:center;margin-bottom:1.75rem; }
    .roy-badge { display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ac-dk);background:var(--ac-lt);border:1px solid rgba(56,168,204,.3);border-radius:20px;padding:4px 12px;margin-bottom:.75rem; }
    .roy-hero h1 { font-family:'Space Grotesk','Sora','Inter',sans-serif;font-size:clamp(1.4rem,5vw,2rem);font-weight:700;color:var(--text);line-height:1.2;margin-bottom:.5rem; }
    .roy-hero p { font-size:13.5px;color:var(--text-3);max-width:500px;margin:0 auto;line-height:1.7; }

    /* Form */
    .roy-form { background:var(--card-bg,rgba(56,168,204,.05));border:1px solid var(--border,rgba(56,168,204,.14));border-radius:18px;padding:1.5rem;margin-bottom:1.25rem; }
    .roy-form-title { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:1rem; }
    .roy-platform { display:grid;grid-template-columns:auto 1fr;align-items:center;gap:10px 14px;margin-bottom:.75rem; }
    .roy-platform-label { display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:var(--text);min-width:140px; }
    .roy-platform-icon { width:22px;height:22px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0; }
    .roy-input { width:100%;padding:9px 13px;border-radius:11px;border:1px solid var(--border);background:var(--bg,#0b1520);color:var(--text);font-size:13px;font-family:inherit;outline:none;transition:.18s; }
    .roy-input:focus { border-color:var(--ac); }
    .roy-input::placeholder { color:var(--text-3); }

    /* Distributor cut */
    .roy-dist { margin-bottom:1rem; }
    .roy-dist label { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);display:block;margin-bottom:.5rem; }
    .roy-dist-row { display:flex;gap:8px;flex-wrap:wrap; }
    .roy-dist-btn { padding:7px 16px;border-radius:20px;border:1px solid var(--border);background:transparent;color:var(--text-3);font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;transition:.18s; }
    .roy-dist-btn.active { background:var(--ac-lt);border-color:var(--ac);color:var(--ac); }

    .roy-calc-btn { width:100%;padding:13px;border-radius:12px;background:linear-gradient(135deg,var(--ac),var(--ac-dk));color:#fff;font-size:14px;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:.18s;letter-spacing:.02em; }
    .roy-calc-btn:hover { opacity:.88;transform:translateY(-1px); }

    /* Results */
    .roy-results { display:none; }
    .roy-results.show { display:block; }

    .roy-total { background:linear-gradient(135deg,var(--ac-lt),var(--card-bg));border:1px solid var(--ac);border-radius:18px;padding:1.5rem;text-align:center;margin-bottom:1.25rem; }
    .roy-total-label { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:.4rem; }
    .roy-total-amount { font-size:2.5rem;font-weight:800;color:var(--ac);font-family:'Space Grotesk','Sora','Inter',sans-serif;line-height:1; }
    .roy-total-usd { font-size:13px;color:var(--text-3);margin-top:.4rem; }
    .roy-total-note { font-size:11.5px;color:var(--text-3);margin-top:.5rem;line-height:1.5; }

    /* Breakdown */
    .roy-breakdown { background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:1.25rem; }
    .roy-bd-title { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);padding:.75rem 1.1rem;border-bottom:1px solid var(--border-2,rgba(56,168,204,.07)); }
    .roy-bd-row { display:grid;grid-template-columns:auto 1fr auto auto;align-items:center;gap:8px 12px;padding:.7rem 1.1rem;border-bottom:1px solid var(--border-2); }
    .roy-bd-row:last-child { border-bottom:none; }
    .roy-bd-platform { display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:var(--text); }
    .roy-bd-streams { font-size:12px;color:var(--text-3); }
    .roy-bd-idr { font-size:13px;font-weight:700;color:var(--text);text-align:right; }
    .roy-bd-bar { height:4px;border-radius:2px;background:var(--ac-lt);overflow:hidden; }
    .roy-bd-bar-inner { height:100%;background:var(--ac);border-radius:2px;transition:.4s; }

    /* Disclaimer */
    .roy-disclaimer { background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);border-radius:12px;padding:.9rem 1.1rem; }
    .roy-disclaimer p { font-size:12px;color:#fbbf24;line-height:1.6; }
    .roy-disclaimer strong { color:#fde68a; }

    /* Tips */
    .roy-tips { background:var(--ac-lt);border:1px solid rgba(56,168,204,.25);border-radius:14px;padding:1rem 1.2rem;margin-top:1.25rem; }
    .roy-tips h3 { font-size:13px;font-weight:700;color:var(--text);margin-bottom:.5rem; }
    .roy-tips ul { list-style:none;padding:0; }
    .roy-tips li { font-size:12.5px;color:var(--text-3);line-height:1.6;padding:3px 0; }
    .roy-tips li::before { content:'→ '; color:var(--ac); }

    @media(max-width:520px) {
        .roy-platform { grid-template-columns:1fr; }
        .roy-platform-label { min-width:unset; }
        .roy-bd-row { grid-template-columns:1fr auto; }
        .roy-bd-streams,.roy-bd-bar { display:none; }
    }
</style>
@endpush

@section('content')
<div class="page-rail-wrap">
<div class="roy-page">
    <a href="{{ route('tools.index') }}" class="roy-back">← Studio Gratis</a>
    @include('partials.tool-share')

    <div class="roy-hero">
        <div class="roy-badge">💰 Royalty Calculator</div>
        <h1>Kalkulator Royalti Streaming</h1>
        <p>Estimasi pendapatan bulanan dari streaming musik kamu di berbagai platform. Masukkan jumlah stream, lihat berapa yang kamu hasilkan.</p>
    </div>

    <div class="roy-form">
        <div class="roy-form-title">Jumlah stream per bulan</div>

        <div class="roy-platform">
            <div class="roy-platform-label">
                <div class="roy-platform-icon" style="background:#1a1a1a;color:#1db954;">♫</div>
                Spotify
            </div>
            <input type="number" class="roy-input" id="inSpotify" placeholder="mis. 10000" min="0">

            <div class="roy-platform-label">
                <div class="roy-platform-icon" style="background:#fc3c44;color:#fff;">♫</div>
                Apple Music
            </div>
            <input type="number" class="roy-input" id="inApple" placeholder="mis. 5000" min="0">

            <div class="roy-platform-label">
                <div class="roy-platform-icon" style="background:#ff0000;color:#fff;">▶</div>
                YouTube Music
            </div>
            <input type="number" class="roy-input" id="inYouTube" placeholder="mis. 8000" min="0">

            <div class="roy-platform-label">
                <div class="roy-platform-icon" style="background:#010101;color:#fff;font-size:11px;">TT</div>
                TikTok
            </div>
            <input type="number" class="roy-input" id="inTiktok" placeholder="mis. 20000" min="0">

            <div class="roy-platform-label">
                <div class="roy-platform-icon" style="background:#3c5a9a;color:#fff;font-size:11px;">in</div>
                Lainnya (avg)
            </div>
            <input type="number" class="roy-input" id="inOther" placeholder="mis. 3000" min="0">
        </div>

        <div class="roy-dist">
            <label>Potongan distributor</label>
            <div class="roy-dist-row" id="distButtons">
                <button class="roy-dist-btn active" data-cut="15">TuneCore (15%)</button>
                <button class="roy-dist-btn" data-cut="20">DistroKid (20%)</button>
                <button class="roy-dist-btn" data-cut="25">Netrilis (25%)</button>
                <button class="roy-dist-btn" data-cut="30">CDBaby (30%)</button>
                <button class="roy-dist-btn" data-cut="0">Langsung (0%)</button>
            </div>
        </div>

        <button class="roy-calc-btn" onclick="calculateRoyalty()">💰 Hitung Estimasi Royalti</button>
    </div>

    <div class="roy-results" id="royResults">
        <div class="roy-total">
            <div class="roy-total-label">Estimasi bersih per bulan</div>
            <div class="roy-total-amount" id="royTotalIdr">Rp 0</div>
            <div class="roy-total-usd" id="royTotalUsd">≈ USD 0</div>
            <div class="roy-total-note" id="royTotalNote"></div>
        </div>

        <div class="roy-breakdown">
            <div class="roy-bd-title">Rincian per platform</div>
            <div id="royBreakdown"></div>
        </div>

        <div class="roy-disclaimer">
            <p><strong>⚠️ Catatan penting:</strong> Angka ini adalah <strong>estimasi</strong> berdasarkan rata-rata per-stream industri (2024). Royalti aktual bervariasi tergantung negara pendengar, tier langganan, dan kebijakan platform yang berubah. Gunakan sebagai gambaran umum, bukan angka pasti.</p>
        </div>

        <div class="roy-tips">
            <h3>💡 Tips tingkatkan pendapatan</h3>
            <ul>
                <li>Spotify playlist editorial = 10× lebih banyak stream — pitch di Spotify for Artists</li>
                <li>Apple Music bayar lebih per stream ($0.01) vs Spotify ($0.003–0.005)</li>
                <li>TikTok viral bisa redirect ke Spotify dalam jumlah besar — optimasi bio link</li>
                <li>Rilis konsisten (1 lagu/bulan) lebih baik dari rilis album setahun sekali</li>
                <li>Distributor dengan flat fee (DistroKid ~$20/tahun) lebih hemat kalau sudah banyak stream</li>
            </ul>
        </div>
    </div>

</div>{{-- .roy-page --}}

<aside class="page-rail-aside">
    @include('partials.content-rail')
</aside>

</div>{{-- .page-rail-wrap --}}
@endsection

@push('scripts')
<script>
(function () {
    // Per-stream rate dalam USD (rata-rata industri 2024)
    var rates = {
        spotify:  0.004,
        apple:    0.010,
        youtube:  0.002,
        tiktok:   0.0015,
        other:    0.003,
    };
    var platformNames = {
        spotify: 'Spotify',
        apple:   'Apple Music',
        youtube: 'YouTube Music',
        tiktok:  'TikTok',
        other:   'Platform Lain',
    };
    var platformColors = {
        spotify: '#1db954',
        apple:   '#fc3c44',
        youtube: '#ff0000',
        tiktok:  '#010101',
        other:   '#38A8CC',
    };

    var selectedCut = 15;

    // Distributor selector
    document.getElementById('distButtons').addEventListener('click', function (e) {
        var btn = e.target.closest('.roy-dist-btn');
        if (!btn) return;
        document.querySelectorAll('.roy-dist-btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        selectedCut = parseInt(btn.dataset.cut);
    });

    var USD_TO_IDR = 16200;

    function fmt(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }
    function fmtUsd(num) {
        return 'USD ' + num.toFixed(2);
    }

    window.calculateRoyalty = function () {
        var inputs = {
            spotify: parseFloat(document.getElementById('inSpotify').value) || 0,
            apple:   parseFloat(document.getElementById('inApple').value)   || 0,
            youtube: parseFloat(document.getElementById('inYouTube').value) || 0,
            tiktok:  parseFloat(document.getElementById('inTiktok').value)  || 0,
            other:   parseFloat(document.getElementById('inOther').value)   || 0,
        };

        var totalStreams = Object.values(inputs).reduce(function (a, b) { return a + b; }, 0);
        if (totalStreams === 0) { return; }

        var cut = selectedCut / 100;
        var platformResults = {};
        var grossUsd = 0;

        Object.keys(inputs).forEach(function (key) {
            var raw = inputs[key] * rates[key];
            platformResults[key] = {
                streams: inputs[key],
                grossUsd: raw,
                netUsd: raw * (1 - cut),
                netIdr: raw * (1 - cut) * USD_TO_IDR,
            };
            grossUsd += raw;
        });

        var netUsd = grossUsd * (1 - cut);
        var netIdr = netUsd * USD_TO_IDR;

        document.getElementById('royTotalIdr').textContent = fmt(netIdr);
        document.getElementById('royTotalUsd').textContent = '≈ ' + fmtUsd(netUsd);
        document.getElementById('royTotalNote').textContent =
            'Dari ' + totalStreams.toLocaleString('id-ID') + ' total stream — setelah potongan distributor ' + selectedCut + '%';

        // Breakdown
        var maxIdr = Math.max(...Object.values(platformResults).map(function (r) { return r.netIdr; }));
        var bdEl   = document.getElementById('royBreakdown');
        bdEl.innerHTML = '';

        Object.keys(platformResults).forEach(function (key) {
            var r = platformResults[key];
            if (r.streams === 0) return;
            var pct  = maxIdr > 0 ? (r.netIdr / maxIdr * 100) : 0;
            var color = platformColors[key];
            var row  = document.createElement('div');
            row.className = 'roy-bd-row';
            row.innerHTML =
                '<div class="roy-bd-platform">' +
                    '<span style="width:8px;height:8px;border-radius:50%;background:' + color + ';flex-shrink:0;"></span>' +
                    platformNames[key] +
                '</div>' +
                '<div class="roy-bd-bar"><div class="roy-bd-bar-inner" style="width:' + pct + '%;background:' + color + ';"></div></div>' +
                '<div class="roy-bd-streams">' + r.streams.toLocaleString('id-ID') + ' stream</div>' +
                '<div class="roy-bd-idr">' + fmt(r.netIdr) + '</div>';
            bdEl.appendChild(row);
        });

        document.getElementById('royResults').classList.add('show');
        document.getElementById('royResults').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };
})();
</script>
@endpush

