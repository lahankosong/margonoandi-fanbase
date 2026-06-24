@extends('layouts.app')

@push('styles')
<style>
    :root { --ac:#38A8CC; --ac-dk:#0ea5e9; --ac-lt:rgba(56,168,204,.12); }

    .bpm-page { max-width:680px; margin:0 auto; padding:1.75rem 1rem 5rem; }
    .bpm-back { display:inline-flex;align-items:center;gap:5px;font-size:13px;color:var(--text-3);text-decoration:none;margin-bottom:1.25rem; }
    .bpm-back:hover { color:var(--text); }

    .bpm-hero { text-align:center;margin-bottom:1.75rem; }
    .bpm-badge { display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ac-dk);background:var(--ac-lt);border:1px solid rgba(56,168,204,.3);border-radius:20px;padding:4px 12px;margin-bottom:.75rem; }
    .bpm-hero h1 { font-family:'Space Grotesk','Sora','Inter',sans-serif;font-size:clamp(1.4rem,5vw,2rem);font-weight:700;color:var(--text);line-height:1.2;margin-bottom:.5rem; }
    .bpm-hero p { font-size:13.5px;color:var(--text-3);max-width:480px;margin:0 auto;line-height:1.7; }

    /* Main tap area */
    .bpm-main { background:var(--card-bg,rgba(56,168,204,.05));border:1px solid var(--border,rgba(56,168,204,.14));border-radius:20px;padding:2rem 1.5rem;text-align:center;margin-bottom:1.25rem; }

    .bpm-display { margin-bottom:1.5rem; }
    .bpm-number { font-size:5rem;font-weight:800;color:var(--text);line-height:1;font-family:'Space Grotesk','Sora','Inter',sans-serif;letter-spacing:-.02em;transition:.15s; }
    .bpm-number.pulse { color:var(--ac);transform:scale(1.06); }
    .bpm-unit { font-size:13px;color:var(--text-3);letter-spacing:.1em;text-transform:uppercase;margin-top:4px; }

    /* Metronome visual */
    .bpm-metro { display:flex;justify-content:center;align-items:center;gap:6px;margin-bottom:1.5rem;height:24px; }
    .bpm-dot { width:10px;height:10px;border-radius:50%;background:var(--border);transition:background .05s,transform .05s; }
    .bpm-dot.beat { background:var(--ac);transform:scale(1.5); }

    .bpm-tap { width:100%;padding:22px;border-radius:16px;background:linear-gradient(135deg,var(--ac),var(--ac-dk));color:#fff;font-size:18px;font-weight:800;border:none;cursor:pointer;font-family:inherit;letter-spacing:.02em;transition:.1s;user-select:none;-webkit-user-select:none;touch-action:manipulation; }
    .bpm-tap:active { transform:scale(.97);opacity:.88; }

    .bpm-hint { font-size:12px;color:var(--text-3);margin-top:.75rem; }

    /* Controls */
    .bpm-controls { display:flex;gap:10px;margin-bottom:1.25rem; }
    .bpm-ctrl { flex:1;padding:11px;border-radius:12px;border:1px solid var(--border);background:var(--card-bg);color:var(--text);font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:.18s; }
    .bpm-ctrl:hover { border-color:var(--ac);color:var(--ac); }

    /* Manual input */
    .bpm-manual { display:flex;gap:10px;align-items:center;margin-bottom:1.25rem; }
    .bpm-manual label { font-size:12px;color:var(--text-3);white-space:nowrap;font-weight:600; }
    .bpm-manual input { flex:1;padding:10px 14px;border-radius:12px;border:1px solid var(--border);background:var(--bg,#0b1520);color:var(--text);font-size:14px;font-weight:700;font-family:inherit;outline:none;text-align:center;transition:.18s; }
    .bpm-manual input:focus { border-color:var(--ac); }
    .bpm-manual-btn { padding:10px 18px;border-radius:12px;background:var(--ac-lt);border:1px solid rgba(56,168,204,.3);color:var(--ac);font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;white-space:nowrap;transition:.18s; }
    .bpm-manual-btn:hover { background:rgba(56,168,204,.2); }

    /* Genre result */
    .bpm-genre-box { background:var(--card-bg);border:1px solid var(--border);border-radius:16px;padding:1.2rem;text-align:left; }
    .bpm-genre-box.hidden { display:none; }
    .bpm-genre-label { font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);margin-bottom:.5rem; }
    .bpm-genre-main { font-size:1.1rem;font-weight:700;color:var(--text);margin-bottom:.3rem; }
    .bpm-genre-sub { font-size:12.5px;color:var(--text-3);line-height:1.6; }
    .bpm-genre-tags { display:flex;gap:6px;flex-wrap:wrap;margin-top:.75rem; }
    .bpm-genre-tag { font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:var(--ac-lt);color:var(--ac);border:1px solid rgba(56,168,204,.25); }

    /* BPM range reference */
    .bpm-ref { margin-top:1.5rem;background:var(--card-bg);border:1px solid var(--border);border-radius:16px;overflow:hidden; }
    .bpm-ref-title { font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-3);padding:.75rem 1.1rem;border-bottom:1px solid var(--border-2,rgba(56,168,204,.07)); }
    .bpm-ref-row { display:flex;align-items:center;padding:.6rem 1.1rem;border-bottom:1px solid var(--border-2);transition:.12s; }
    .bpm-ref-row:last-child { border-bottom:none; }
    .bpm-ref-row.current { background:var(--ac-lt); }
    .bpm-ref-range { font-size:12px;font-weight:700;color:var(--ac);min-width:80px; }
    .bpm-ref-name { font-size:12.5px;color:var(--text);flex:1; }
    .bpm-ref-ex { font-size:11px;color:var(--text-3); }
</style>
@endpush

@section('content')
<div class="bpm-page">
    <a href="{{ route('tools.index') }}" class="bpm-back">← Studio Gratis</a>
    @include('partials.tool-share')

    <div class="bpm-hero">
        <div class="bpm-badge">🥁 BPM Calculator</div>
        <h1>Kalkulator BPM & Tap Tempo</h1>
        <p>Ketuk tombol mengikuti ritme lagu untuk tahu BPM-nya. Atau masukkan BPM manual dan lihat saran genre yang cocok.</p>
    </div>

    <div class="bpm-main">
        <div class="bpm-display">
            <div class="bpm-number" id="bpmNumber">—</div>
            <div class="bpm-unit">Beats Per Minute</div>
        </div>

        <div class="bpm-metro" id="bpmMetro">
            <div class="bpm-dot" id="d0"></div>
            <div class="bpm-dot" id="d1"></div>
            <div class="bpm-dot" id="d2"></div>
            <div class="bpm-dot" id="d3"></div>
        </div>

        <button class="bpm-tap" id="bpmTapBtn">🥁 TAP</button>
        <div class="bpm-hint">Ketuk minimal 4× untuk hasil akurat. Tekan <kbd>Spasi</kbd> juga bisa.</div>
    </div>

    <div class="bpm-controls">
        <button class="bpm-ctrl" onclick="resetBpm()">↺ Reset</button>
        <button class="bpm-ctrl" onclick="bpmMinus()">− 1 BPM</button>
        <button class="bpm-ctrl" onclick="bpmPlus()">+ 1 BPM</button>
        <button class="bpm-ctrl" id="bpmMetroToggle" onclick="toggleMetronome()">▶ Metronome</button>
    </div>

    <div class="bpm-manual">
        <label for="bpmManual">Masukkan BPM:</label>
        <input type="number" id="bpmManual" min="20" max="300" placeholder="mis. 120">
        <button class="bpm-manual-btn" onclick="applyManual()">Terapkan</button>
    </div>

    <div class="bpm-genre-box hidden" id="bpmGenreBox">
        <div class="bpm-genre-label">Genre yang cocok</div>
        <div class="bpm-genre-main" id="bpmGenreMain">—</div>
        <div class="bpm-genre-sub" id="bpmGenreSub">—</div>
        <div class="bpm-genre-tags" id="bpmGenreTags"></div>
    </div>

    <div class="bpm-ref">
        <div class="bpm-ref-title">Referensi BPM per Genre</div>
        <div class="bpm-ref-row" data-min="40"  data-max="59"  id="ref0"><span class="bpm-ref-range">40–59</span><span class="bpm-ref-name">Sangat Lambat / Larghissimo</span><span class="bpm-ref-ex">Ambient, ballad lambat</span></div>
        <div class="bpm-ref-row" data-min="60"  data-max="75"  id="ref1"><span class="bpm-ref-range">60–75</span><span class="bpm-ref-name">Ballad / Slow</span><span class="bpm-ref-ex">Love song, acoustic ballad</span></div>
        <div class="bpm-ref-row" data-min="76"  data-max="95"  id="ref2"><span class="bpm-ref-range">76–95</span><span class="bpm-ref-name">Moderate / Folk</span><span class="bpm-ref-ex">Folk, indie pop, R&B</span></div>
        <div class="bpm-ref-row" data-min="96"  data-max="115" id="ref3"><span class="bpm-ref-range">96–115</span><span class="bpm-ref-name">Pop / Mid-tempo</span><span class="bpm-ref-ex">Pop, rock mid-tempo</span></div>
        <div class="bpm-ref-row" data-min="116" data-max="135" id="ref4"><span class="bpm-ref-range">116–135</span><span class="bpm-ref-name">Upbeat / Rock</span><span class="bpm-ref-ex">Rock, upbeat pop, dance</span></div>
        <div class="bpm-ref-row" data-min="136" data-max="160" id="ref5"><span class="bpm-ref-range">136–160</span><span class="bpm-ref-name">Fast / Electronic</span><span class="bpm-ref-ex">EDM, punk, metal ringan</span></div>
        <div class="bpm-ref-row" data-min="161" data-max="300" id="ref6"><span class="bpm-ref-range">161+</span><span class="bpm-ref-name">Sangat Cepat</span><span class="bpm-ref-ex">Drum & bass, speedcore, metal</span></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var taps       = [];
    var currentBpm = 0;
    var metroTimer = null;
    var beatIdx    = 0;
    var isMetroOn  = false;
    var tapResetTimer = null;

    var genres = [
        { min:40,  max:59,  main:'Sangat Lambat',  sub:'Cocok untuk ambient, score film, atau ballad yang sangat lambat dan atmospheric.', tags:['Ambient','Cinematic','Drone'] },
        { min:60,  max:75,  main:'Ballad / Slow',   sub:'BPM ideal untuk love song, acoustic ballad, dan lagu yang butuh kedalaman emosional.', tags:['Ballad','Acoustic','Soul'] },
        { min:76,  max:95,  main:'Folk / Moderate', sub:'Tempo santai yang cocok untuk folk, indie pop, R&B, dan lagu dengan groove yang smooth.', tags:['Folk','Indie Pop','R&B','Bossa Nova'] },
        { min:96,  max:115, main:'Pop / Mid-tempo', sub:'Range paling populer di pop modern. Enak untuk nyanyi, dance ringan, dan catchy.', tags:['Pop','Soft Rock','K-Pop','OPM'] },
        { min:116, max:135, main:'Upbeat / Rock',   sub:'Energetik dan enerjik. Cocok untuk rock, dance pop, dan lagu yang bikin semangat.', tags:['Rock','Dance Pop','Funk','Hip-Hop'] },
        { min:136, max:160, main:'Fast / EDM',      sub:'Cepat dan intens. Dominan di EDM, punk, metal, dan music yang butuh drive kuat.', tags:['EDM','Punk','Metal','Drum & Bass'] },
        { min:161, max:300, main:'Sangat Cepat',    sub:'Ekstrem. Khusus genre teknikal seperti speedcore, blast beat metal, atau drum & bass cepat.', tags:['Speedcore','Metal','Drum & Bass'] },
    ];

    function updateDisplay(bpm) {
        currentBpm = bpm;
        var numEl = document.getElementById('bpmNumber');
        numEl.textContent = Math.round(bpm);
        numEl.classList.add('pulse');
        setTimeout(function () { numEl.classList.remove('pulse'); }, 150);
        updateGenre(bpm);
        highlightRef(bpm);
    }

    function updateGenre(bpm) {
        var box = document.getElementById('bpmGenreBox');
        var g = genres.find(function (g) { return bpm >= g.min && bpm <= g.max; });
        if (!g) { box.classList.add('hidden'); return; }
        box.classList.remove('hidden');
        document.getElementById('bpmGenreMain').textContent = g.main;
        document.getElementById('bpmGenreSub').textContent  = g.sub;
        document.getElementById('bpmGenreTags').innerHTML   = g.tags.map(function (t) {
            return '<span class="bpm-genre-tag">' + t + '</span>';
        }).join('');
    }

    function highlightRef(bpm) {
        document.querySelectorAll('.bpm-ref-row').forEach(function (row) {
            var mn = parseInt(row.dataset.min), mx = parseInt(row.dataset.max);
            row.classList.toggle('current', bpm >= mn && bpm <= mx);
        });
    }

    function tap() {
        var now = Date.now();

        // Reset jika jeda > 3 detik
        if (taps.length > 0 && now - taps[taps.length - 1] > 3000) {
            taps = [];
        }
        taps.push(now);

        // Jaga maksimal 16 tap terakhir
        if (taps.length > 16) taps.shift();

        if (taps.length >= 2) {
            var intervals = [];
            for (var i = 1; i < taps.length; i++) {
                intervals.push(taps[i] - taps[i - 1]);
            }
            var avg = intervals.reduce(function (a, b) { return a + b; }, 0) / intervals.length;
            var bpm = 60000 / avg;
            bpm = Math.min(300, Math.max(20, bpm));
            updateDisplay(bpm);

            if (isMetroOn) { restartMetronome(bpm); }
        }

        // Animasi dot
        flashDot();

        // Auto-reset taps setelah 4 detik tanpa tap
        clearTimeout(tapResetTimer);
        tapResetTimer = setTimeout(function () { taps = []; }, 4000);
    }

    var dotIdx = 0;
    function flashDot() {
        var dots = document.querySelectorAll('.bpm-dot');
        dots.forEach(function (d) { d.classList.remove('beat'); });
        dots[dotIdx % 4].classList.add('beat');
        dotIdx++;
        setTimeout(function () { dots.forEach(function (d) { d.classList.remove('beat'); }); }, 120);
    }

    function resetBpm() {
        taps = []; currentBpm = 0; dotIdx = 0; beatIdx = 0;
        stopMetronome();
        document.getElementById('bpmNumber').textContent = '—';
        document.getElementById('bpmGenreBox').classList.add('hidden');
        document.getElementById('bpmManual').value = '';
        document.querySelectorAll('.bpm-ref-row').forEach(function (r) { r.classList.remove('current'); });
        document.getElementById('bpmMetroToggle').textContent = '▶ Metronome';
        isMetroOn = false;
    }

    window.bpmMinus = function () { if (currentBpm > 21) updateDisplay(currentBpm - 1); };
    window.bpmPlus  = function () { if (currentBpm < 299) updateDisplay(currentBpm + 1); };

    window.applyManual = function () {
        var val = parseInt(document.getElementById('bpmManual').value);
        if (!isNaN(val) && val >= 20 && val <= 300) {
            taps = [];
            updateDisplay(val);
            if (isMetroOn) restartMetronome(val);
        }
    };

    // ─── Metronome ───────────────────────────────────────────────────────
    function stopMetronome() {
        if (metroTimer) { clearInterval(metroTimer); metroTimer = null; }
        document.querySelectorAll('.bpm-dot').forEach(function (d) { d.classList.remove('beat'); });
    }

    function restartMetronome(bpm) {
        stopMetronome();
        var interval = 60000 / bpm;
        beatIdx = 0;
        metroTimer = setInterval(function () {
            var dots = document.querySelectorAll('.bpm-dot');
            dots.forEach(function (d) { d.classList.remove('beat'); });
            dots[beatIdx % 4].classList.add('beat');
            beatIdx++;
            setTimeout(function () { dots.forEach(function (d) { d.classList.remove('beat'); }); }, Math.min(100, interval * 0.4));
        }, interval);
    }

    window.toggleMetronome = function () {
        if (!currentBpm) return;
        isMetroOn = !isMetroOn;
        document.getElementById('bpmMetroToggle').textContent = isMetroOn ? '⏹ Stop Metro' : '▶ Metronome';
        if (isMetroOn) restartMetronome(currentBpm);
        else stopMetronome();
    };

    // ─── Event listeners ─────────────────────────────────────────────────
    document.getElementById('bpmTapBtn').addEventListener('click', tap);
    document.getElementById('bpmTapBtn').addEventListener('touchstart', function (e) { e.preventDefault(); tap(); }, { passive: false });

    document.addEventListener('keydown', function (e) {
        if (e.code === 'Space' && e.target.tagName !== 'INPUT') { e.preventDefault(); tap(); }
    });
})();
</script>
@endpush
