@extends('layouts.app')

@push('styles')
<style>
    :root { --ac:#38A8CC; --ac-dk:#0ea5e9; --ac-lt:rgba(56,168,204,.12); }

    .cb-page { max-width:760px; margin:0 auto; padding:1.75rem 1rem 5rem; }
    .cb-back { display:inline-flex;align-items:center;gap:5px;font-size:13px;color:var(--text-3,#94a3b8);text-decoration:none;margin-bottom:1.25rem; }
    .cb-back:hover { color:var(--text); }

    .cb-hero { text-align:center;margin-bottom:1.75rem; }
    .cb-badge { display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ac-dk);background:var(--ac-lt);border:1px solid rgba(56,168,204,.3);border-radius:20px;padding:4px 12px;margin-bottom:.75rem; }
    .cb-hero h1 { font-family:'Space Grotesk','Sora','Inter',sans-serif;font-size:clamp(1.4rem,5vw,2rem);font-weight:700;color:var(--text);line-height:1.2;margin-bottom:.5rem; }
    .cb-hero p { font-size:13.5px;color:var(--text-3);max-width:520px;margin:0 auto;line-height:1.7; }

    /* Control panel */
    .cb-panel { background:var(--card-bg,rgba(56,168,204,.05));border:1px solid var(--border,rgba(56,168,204,.14));border-radius:18px;padding:1.5rem;margin-bottom:1.5rem; }
    .cb-row { display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:1rem; }
    .cb-row:last-child { margin-bottom:0; }
    .cb-field label { display:block;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-3);margin-bottom:6px; }
    .cb-select { width:100%;padding:10px 14px;border-radius:12px;background:var(--bg,#0b1520);border:1px solid var(--border);color:var(--text);font-family:inherit;font-size:13px;cursor:pointer;outline:none;transition:.18s; }
    .cb-select:focus { border-color:var(--ac); }
    .cb-btn { width:100%;padding:13px;border-radius:12px;background:linear-gradient(135deg,var(--ac),var(--ac-dk));color:#fff;font-size:14px;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:.18s;letter-spacing:.02em; }
    .cb-btn:hover { opacity:.88;transform:translateY(-1px); }
    .cb-btn:active { transform:none; }

    /* Results */
    .cb-results { display:none; }
    .cb-results.show { display:block; }

    .cb-result-title { font-size:13px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem; }

    .cb-prog { background:var(--card-bg);border:1px solid var(--border);border-radius:16px;padding:1.1rem 1.2rem;margin-bottom:10px;cursor:pointer;transition:.18s; }
    .cb-prog:hover { border-color:var(--ac); }
    .cb-prog-top { display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px; }
    .cb-prog-name { font-size:13px;font-weight:700;color:var(--text-3); }
    .cb-prog-vibe { font-size:12px;color:var(--text-3);font-style:italic; }
    .cb-chords { display:flex;gap:8px;flex-wrap:wrap; }
    .cb-chord { background:var(--bg,#0b1520);border:1px solid var(--border);border-radius:10px;padding:8px 14px;text-align:center;min-width:52px; }
    .cb-chord-name { font-size:15px;font-weight:700;color:var(--text);line-height:1; }
    .cb-chord-degree { font-size:10px;color:var(--text-3);margin-top:3px; }

    .cb-prog-detail { display:none;margin-top:10px;padding-top:10px;border-top:1px solid var(--border-2,rgba(56,168,204,.07)); }
    .cb-prog-detail.open { display:block; }
    .cb-prog-detail p { font-size:12.5px;color:var(--text-3);line-height:1.7; }
    .cb-prog-detail .cb-usage { margin-top:6px;font-size:12px;color:var(--ac); }

    /* Tips box */
    .cb-tips { background:var(--ac-lt);border:1px solid rgba(56,168,204,.25);border-radius:14px;padding:1rem 1.2rem;margin-top:1.5rem; }
    .cb-tips h3 { font-size:13px;font-weight:700;color:var(--text);margin-bottom:.5rem; }
    .cb-tips ul { list-style:none;padding:0; }
    .cb-tips li { font-size:12.5px;color:var(--text-3);line-height:1.6;padding:3px 0; }
    .cb-tips li::before { content:'→ '; color:var(--ac); }

    @media(max-width:520px) {
        .cb-row { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="cb-page">
    <a href="{{ route('tools.index') }}" class="cb-back">← Studio Gratis</a>
    @include('partials.tool-share')

    <div class="cb-hero">
        <div class="cb-badge">🎸 Chord Builder</div>
        <h1>Chord Progression Generator</h1>
        <p>Pilih kunci &amp; suasana, dapatkan 5 progresi chord siap pakai. Cocok buat musisi yang mau eksplor harmoni baru atau cari inspirasi lagu.</p>
    </div>

    <div class="cb-panel">
        <div class="cb-row">
            <div class="cb-field">
                <label for="cbKey">Kunci (Key)</label>
                <select id="cbKey" class="cb-select">
                    <option value="C">C Mayor</option>
                    <option value="C#">C# / Db Mayor</option>
                    <option value="D">D Mayor</option>
                    <option value="D#">D# / Eb Mayor</option>
                    <option value="E">E Mayor</option>
                    <option value="F">F Mayor</option>
                    <option value="F#">F# / Gb Mayor</option>
                    <option value="G">G Mayor</option>
                    <option value="G#">G# / Ab Mayor</option>
                    <option value="A">A Mayor</option>
                    <option value="A#">A# / Bb Mayor</option>
                    <option value="B">B Mayor</option>
                    <option value="Cm">C Minor</option>
                    <option value="C#m">C#m / Dbm Minor</option>
                    <option value="Dm">D Minor</option>
                    <option value="D#m">D#m / Ebm Minor</option>
                    <option value="Em">E Minor</option>
                    <option value="Fm">F Minor</option>
                    <option value="F#m">F#m / Gbm Minor</option>
                    <option value="Gm">G Minor</option>
                    <option value="G#m">G#m / Abm Minor</option>
                    <option value="Am">A Minor</option>
                    <option value="A#m">A#m / Bbm Minor</option>
                    <option value="Bm">B Minor</option>
                </select>
            </div>
            <div class="cb-field">
                <label for="cbMood">Suasana (Mood)</label>
                <select id="cbMood" class="cb-select">
                    <option value="happy">Ceria / Optimis</option>
                    <option value="sad">Sedih / Melankolis</option>
                    <option value="epic">Epik / Dramatis</option>
                    <option value="romantic">Romantis / Intim</option>
                    <option value="dark">Gelap / Misterius</option>
                    <option value="hopeful">Penuh Harapan</option>
                </select>
            </div>
        </div>
        <div class="cb-row">
            <div class="cb-field" style="grid-column:1/-1;">
                <button class="cb-btn" id="cbGenerate" onclick="generateChords()">🎸 Generate Progresi Chord</button>
            </div>
        </div>
    </div>

    <div class="cb-results" id="cbResults">
        <div class="cb-result-title" id="cbResultTitle">5 Progresi untuk <span id="cbKeyLabel">C Mayor</span> — <span id="cbMoodLabel">Ceria</span></div>
        <div id="cbProgressions"></div>

        <div class="cb-tips" id="cbTips">
            <h3>💡 Tips pakai progresi ini</h3>
            <ul id="cbTipsList"></ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ─── Data tabel nada per key ───────────────────────────────────────────
    var majorScales = {
        'C':  ['C','Dm','Em','F','G','Am','Bdim'],
        'C#': ['C#','D#m','E#m','F#','G#','A#m','B#dim'],
        'D':  ['D','Em','F#m','G','A','Bm','C#dim'],
        'D#': ['D#','Fm','Gm','G#','A#','Cm','Ddim'],
        'E':  ['E','F#m','G#m','A','B','C#m','D#dim'],
        'F':  ['F','Gm','Am','Bb','C','Dm','Edim'],
        'F#': ['F#','G#m','A#m','B','C#','D#m','E#dim'],
        'G':  ['G','Am','Bm','C','D','Em','F#dim'],
        'G#': ['G#','A#m','Cm','C#','D#','Fm','Gdim'],
        'A':  ['A','Bm','C#m','D','E','F#m','G#dim'],
        'A#': ['A#','Cm','Dm','D#','F','Gm','Adim'],
        'B':  ['B','C#m','D#m','E','F#','G#m','A#dim'],
    };
    var minorScales = {
        'Cm':  ['Cm','Ddim','Eb','Fm','Gm','Ab','Bb'],
        'C#m': ['C#m','D#dim','E','F#m','G#m','A','B'],
        'Dm':  ['Dm','Edim','F','Gm','Am','Bb','C'],
        'D#m': ['D#m','Fdim','F#','G#m','A#m','B','C#'],
        'Em':  ['Em','F#dim','G','Am','Bm','C','D'],
        'Fm':  ['Fm','Gdim','Ab','Bbm','Cm','Db','Eb'],
        'F#m': ['F#m','G#dim','A','Bm','C#m','D','E'],
        'Gm':  ['Gm','Adim','Bb','Cm','Dm','Eb','F'],
        'G#m': ['G#m','A#dim','B','C#m','D#m','E','F#'],
        'Am':  ['Am','Bdim','C','Dm','Em','F','G'],
        'A#m': ['A#m','Cdim','C#','D#m','Fm','F#','G#'],
        'Bm':  ['Bm','C#dim','D','Em','F#m','G','A'],
    };

    // Degree labels
    var majorDeg = ['I','ii','iii','IV','V','vi','vii°'];
    var minorDeg = ['i','ii°','III','iv','v','VI','VII'];

    // ─── Pola progresi (indeks 0-based dari scale) ─────────────────────────
    var patterns = {
        happy: [
            { name:'I–V–vi–IV',   degrees:[0,4,5,3], vibe:'Paling populer di pop modern — ceria, satisfying, earworm.',      usage:'Dipakai: Shape of You, Someone Like You, ribuan lagu pop.' },
            { name:'I–IV–V–I',    degrees:[0,3,4,0], vibe:'Klasik rock & blues — straight-forward, energetik.',               usage:'Dipakai: lagu rock klasik, country, blues.' },
            { name:'I–IV–I–V',    degrees:[0,3,0,4], vibe:'Bounce yang fun — cocok untuk intro atau verse yang upbeat.',      usage:'Dipakai: lagu folk, indie pop, children songs.' },
            { name:'I–V–IV–I',    degrees:[0,4,3,0], vibe:'Power anthem — besar, terbuka, penuh semangat.',                   usage:'Dipakai: hymn, lagu perjuangan, rock anthem.' },
            { name:'I–ii–IV–V',   degrees:[0,1,3,4], vibe:'Smooth & catchy — ada tension yang menyenangkan di ii.',           usage:'Dipakai: pop, soul, R&B.' },
        ],
        sad: [
            { name:'i–VI–III–VII', degrees:[0,5,2,6], vibe:'Melankolis dalam — sering disebut "andalusian cadence".',          usage:'Dipakai: lagu sedih dramatis, OST film.' },
            { name:'i–iv–i–V',     degrees:[0,3,0,4], vibe:'Sedih klasik — minor yang langsung menyentuh hati.',               usage:'Dipakai: ballad sedih, lagu patah hati.' },
            { name:'i–VI–VII–i',   degrees:[0,5,6,0], vibe:'Sirkular yang melankolis — terasa berputar seperti pikiran.',      usage:'Dipakai: indie melankolis, lagu introspektif.' },
            { name:'i–III–VII–VI', degrees:[0,2,6,5], vibe:'Sedih tapi indah — ada harapan di tengah kesedihan.',             usage:'Dipakai: slow rock, pop emosional.' },
            { name:'i–v–VI–VII',   degrees:[0,4,5,6], vibe:'Dark & bittersweet — terasa berat tapi mengalir.',                 usage:'Dipakai: alternative, post-rock.' },
        ],
        epic: [
            { name:'I–VI–IV–V',   degrees:[0,5,3,4], vibe:'Epic & dramatis — build-up yang kuat menuju resolusi.',            usage:'Dipakai: soundtrack film, ballad rock.' },
            { name:'I–V–vi–iii',  degrees:[0,4,5,2], vibe:'Naik dramatis — terasa journey yang panjang.',                     usage:'Dipakai: cinematic music, power ballad.' },
            { name:'i–VII–VI–VII', degrees:[0,6,5,6], vibe:'Back-and-forth yang mencekam — tegangan tanpa henti.',             usage:'Dipakai: battle scenes, intense moments.' },
            { name:'I–iii–IV–I',  degrees:[0,2,3,0], vibe:'Heroik — ada rasa kebanggaan dan pencapaian.',                     usage:'Dipakai: victory themes, sports anthems.' },
            { name:'i–VI–III–VII',degrees:[0,5,2,6], vibe:'Andalusian epic — dramatis & tak terduga.',                        usage:'Dipakai: flamenco, OST film action.' },
        ],
        romantic: [
            { name:'I–vi–IV–V',   degrees:[0,5,3,4], vibe:'Romantis klasik — smooth, sweet, timeless.',                      usage:'Dipakai: love songs classic, wedding songs.' },
            { name:'I–iii–vi–IV', degrees:[0,2,5,3], vibe:'Intim & hangat — perjalanan emosional yang lembut.',               usage:'Dipakai: OPM, ballad romantis.' },
            { name:'I–vi–ii–V',   degrees:[0,5,1,4], vibe:'Jazz-influenced romance — sophisticated, elegan.',                 usage:'Dipakai: jazz standards, bossa nova.' },
            { name:'I–IV–vi–V',   degrees:[0,3,5,4], vibe:'Cinta modern — catchy & sentimental sekaligus.',                   usage:'Dipakai: pop romantis, K-pop.' },
            { name:'i–VI–III–VII',degrees:[0,5,2,6], vibe:'Cinta yang rumit — ada kerinduan dan kedalaman.',                  usage:'Dipakai: OPM minor, ballad gelap.' },
        ],
        dark: [
            { name:'i–VII–VI–VII', degrees:[0,6,5,6], vibe:'Oscillating darkness — terasa terjebak, berulang.',               usage:'Dipakai: alternative rock, dark pop.' },
            { name:'i–ii°–V–i',   degrees:[0,1,4,0], vibe:'Tension klasik — resolusi yang berat & misterius.',                usage:'Dipakai: film noir, thriller music.' },
            { name:'i–VI–iv–V',   degrees:[0,5,3,4], vibe:'Dark dengan resolusi — ada cahaya di ujung kegelapan.',            usage:'Dipakai: gothic rock, doom metal ringan.' },
            { name:'i–III–vi–VII',degrees:[0,2,5,6], vibe:'Sinister & mengintai — tidak terduga, misterius.',                 usage:'Dipakai: horror OST, dark ambient.' },
            { name:'i–iv–VII–III',degrees:[0,3,6,2], vibe:'Lingkaran setan — terasa tidak berakhir, obsesif.',                usage:'Dipakai: psychedelic, experimental.' },
        ],
        hopeful: [
            { name:'I–V–vi–IV',   degrees:[0,4,5,3], vibe:'Harapan universal — feel-good tanpa kehilangan depth.',            usage:'Dipakai: anthem harapan, lagu motivasi.' },
            { name:'I–IV–I–V',    degrees:[0,3,0,4], vibe:'Terbuka & optimis — seperti hari baru.',                           usage:'Dipakai: folk, gospel modern.' },
            { name:'i–VI–III–VII',degrees:[0,5,2,6], vibe:'Harapan di tengah gelap — minor yang tetap terasa uplifting.',     usage:'Dipakai: lagu perjuangan, motivational.' },
            { name:'I–ii–iii–IV', degrees:[0,1,2,3], vibe:'Ascending hope — terasa naik seperti semangat yang tumbuh.',       usage:'Dipakai: cinematic hope, soundtrack inspirasi.' },
            { name:'I–vi–I–V',    degrees:[0,5,0,4], vibe:'Sederhana tapi kuat — ketenangan yang penuh kepercayaan.',         usage:'Dipakai: hymn, spiritual pop.' },
        ],
    };

    var moodLabels = {
        happy:'Ceria / Optimis', sad:'Sedih / Melankolis', epic:'Epik / Dramatis',
        romantic:'Romantis / Intim', dark:'Gelap / Misterius', hopeful:'Penuh Harapan'
    };
    var tipsMap = {
        happy:    ['Mulai dengan I chord untuk langsung terasa positif', 'Pakai tempo 100–130 BPM untuk feel ceria', 'Tambah major 7th untuk nuansa lebih modern'],
        sad:      ['Tempo 60–80 BPM untuk feel melankolis yang dalam', 'Coba arpeggio chord (satu nada satu-satu) bukan strum', 'Minor 9 chord bisa tambah depth emosional'],
        epic:     ['Build up pelan dari verse ke chorus — jangan langsung besar', 'Pakai octave doubling di melodi untuk terasa grand', 'Tambah perkusi yang kuat di resolusi progresi'],
        romantic: ['Tempo 70–90 BPM untuk feel intim', 'Fingerpicking lebih romantis dari strum biasa', 'Sus2 dan add9 chord cocok untuk nuansa lembut'],
        dark:     ['Tempo lambat atau swing untuk feel misterius', 'Coba drop-D tuning untuk resonansi lebih dalam', 'Diminished chord di passing note tambah ketegangan'],
        hopeful:  ['Naik oktaf di bridge untuk puncak emosional', 'Add9 atau maj7 chord terasa hopeful tanpa terlalu pop', 'Tempo medium 85–110 BPM pas untuk feel ini'],
    };

    window.generateChords = function () {
        var keyVal  = document.getElementById('cbKey').value;
        var moodVal = document.getElementById('cbMood').value;

        var isMinor = keyVal.endsWith('m');
        var scale   = isMinor ? minorScales[keyVal] : majorScales[keyVal];
        var degrees = isMinor ? minorDeg : majorDeg;
        var pats    = patterns[moodVal];

        // Tampilkan label
        document.getElementById('cbKeyLabel').textContent  = keyVal + (isMinor ? '' : ' Mayor');
        document.getElementById('cbMoodLabel').textContent = moodLabels[moodVal];

        var container = document.getElementById('cbProgressions');
        container.innerHTML = '';

        pats.forEach(function (pat, idx) {
            var chordsHtml = pat.degrees.map(function (di) {
                var chord = scale[di] || '?';
                var deg   = degrees[di] || '';
                return '<div class="cb-chord"><div class="cb-chord-name">' + chord + '</div><div class="cb-chord-degree">' + deg + '</div></div>';
            }).join('');

            var card = document.createElement('div');
            card.className = 'cb-prog';
            card.innerHTML =
                '<div class="cb-prog-top">' +
                    '<span class="cb-prog-name">' + pat.name + '</span>' +
                    '<span class="cb-prog-vibe">' + (idx === 0 ? '⭐ Paling direkomendasikan' : 'Klik untuk detail') + '</span>' +
                '</div>' +
                '<div class="cb-chords">' + chordsHtml + '</div>' +
                '<div class="cb-prog-detail' + (idx === 0 ? ' open' : '') + '">' +
                    '<p>' + pat.vibe + '</p>' +
                    '<p class="cb-usage">' + pat.usage + '</p>' +
                '</div>';

            card.addEventListener('click', function () {
                var detail = card.querySelector('.cb-prog-detail');
                detail.classList.toggle('open');
            });
            container.appendChild(card);
        });

        // Tips
        var tips = tipsMap[moodVal] || [];
        var tipsList = document.getElementById('cbTipsList');
        tipsList.innerHTML = tips.map(function (t) { return '<li>' + t + '</li>'; }).join('');

        document.getElementById('cbResults').classList.add('show');
        document.getElementById('cbResults').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };
})();
</script>
@endpush
