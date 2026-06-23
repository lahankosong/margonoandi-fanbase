<?php
/**
 * run_patch.php — feat: Pemotong Lagu Online + fix admin audio-cut
 * Akses: https://margonoandi.my.id/run_patch.php?key=<DEPLOY_KEY>
 * Kunci dibaca dari .env (DEPLOY_KEY) — hapus file ini setelah berhasil.
 */

$base = realpath(__DIR__ . '/../');

// ── Auth via DEPLOY_KEY dari .env (sama seperti deploy.php) ──────────────────
$secret = '';
$envFile = $base . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strncmp($line, 'DEPLOY_KEY=', 11) === 0) {
            $secret = trim(substr($line, 11), " \t\"'");
            break;
        }
    }
}
if ($secret === '' || !hash_equals($secret, (string)($_GET['key'] ?? ''))) {
    http_response_code(403);
    die('<!DOCTYPE html><html><body style="font-family:sans-serif;background:#0b1520;color:#ef4444;padding:2rem">
    <h2>403 — DEPLOY_KEY salah atau belum diset di .env</h2></body></html>');
}

// ── Helpers ───────────────────────────────────────────────────────────────────
$steps = [];

function patchWrite(string $path, string $content): array {
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        return ['ok' => false, 'msg' => 'Gagal buat direktori: ' . $dir];
    }
    $bytes = file_put_contents($path, $content);
    return $bytes !== false
        ? ['ok' => true,  'msg' => 'Ditulis (' . round($bytes / 1024, 1) . ' KB)']
        : ['ok' => false, 'msg' => 'file_put_contents gagal — cek permission'];
}

function patchReplace(string $path, string $old, string $new, string $alreadyMark = ''): array {
    if (!file_exists($path)) return ['ok' => false, 'msg' => 'File tidak ditemukan: ' . basename($path)];
    $content = file_get_contents($path);
    $checkMark = $alreadyMark ?: $new;
    if (strpos($content, $checkMark) !== false) return ['ok' => true, 'msg' => 'Sudah diterapkan (skip)'];
    if (strpos($content, $old) === false)       return ['ok' => false, 'msg' => 'Marker lama tidak ditemukan — file mungkin sudah berbeda'];
    $result = file_put_contents($path, str_replace($old, $new, $content));
    return $result !== false
        ? ['ok' => true,  'msg' => 'Di-patch']
        : ['ok' => false, 'msg' => 'Gagal tulis file'];
}

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 1 — ToolController.php (file baru)
// ═════════════════════════════════════════════════════════════════════════════
$toolController = <<<'PHP_EOF'
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function audioCutter()
    {
        return view('tools.audio-cutter', [
            'seo' => [
                'title'       => 'Pemotong Lagu Online Gratis — Potong MP3, WAV, OGG di Browser',
                'description' => 'Potong bagian lagu favoritmu secara online, gratis, tanpa upload ke server. Mendukung MP3, WAV, OGG, FLAC. Hasil langsung diunduh ke perangkatmu.',
                'canonical'   => url('/tools/potong-lagu'),
            ],
        ]);
    }
}
PHP_EOF;

$steps[] = ['label' => 'Buat app/Http/Controllers/ToolController.php']
         + patchWrite($base . '/app/Http/Controllers/ToolController.php', $toolController);

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 2 — resources/views/tools/audio-cutter.blade.php (file baru)
// ═════════════════════════════════════════════════════════════════════════════
$audioCutter = <<<'BLADE_EOF'
@extends('layouts.app')

@push('head')
<meta name="description" content="{{ $seo['description'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Pemotong Lagu Online",
  "url": "{{ $seo['canonical'] }}",
  "description": "{{ $seo['description'] }}",
  "applicationCategory": "MultimediaApplication",
  "operatingSystem": "Any",
  "offers": { "@type": "Offer", "price": "0", "priceCurrency": "IDR" }
}
</script>
@endpush

@section('title', $seo['title'])

@push('styles')
<style>
    :root {
        --ac: #38bdf8;
        --ac-dk: #0ea5e9;
        --ac-lt: rgba(56,189,248,.12);
        --or: #f59e0b;
        --rd: #ef4444;
        --green: #22c55e;
    }

    .ac-page { max-width: 780px; margin: 0 auto; padding: 1.5rem 1rem 4rem; }

    .ac-hero { text-align: center; margin-bottom: 2rem; }
    .ac-hero-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--ac-dk, #0ea5e9); background: var(--ac-lt, rgba(56,189,248,.1)); border: 1px solid rgba(56,189,248,.3); border-radius: 20px; padding: 4px 12px; margin-bottom: 1rem; }
    .ac-hero h1 { font-family: 'Space Grotesk','Sora','Inter',sans-serif; font-size: clamp(1.5rem, 5vw, 2.2rem); font-weight: 700; color: var(--text, #f0f0f0); line-height: 1.2; margin-bottom: .6rem; }
    .ac-hero p { font-size: 14px; color: var(--text-3, #94a3b8); max-width: 520px; margin: 0 auto; line-height: 1.7; }

    .ac-drop { border: 2px dashed var(--border, #334155); border-radius: 20px; padding: 2.5rem 1.5rem; text-align: center; cursor: pointer; transition: .2s; background: var(--card-bg, #0f172a); }
    .ac-drop:hover, .ac-drop.drag-over { border-color: var(--ac); background: var(--ac-lt); }
    .ac-drop-icon { font-size: 2.5rem; margin-bottom: .75rem; }
    .ac-drop-text { font-size: 15px; font-weight: 600; color: var(--text, #f0f0f0); margin-bottom: .3rem; }
    .ac-drop-sub { font-size: 12px; color: var(--text-3, #94a3b8); }
    #acFileInput { display: none; }

    .ac-editor { background: var(--card-bg, #0f172a); border: 1px solid var(--border, #334155); border-radius: 20px; overflow: hidden; margin-top: 1.25rem; display: none; }
    .ac-editor.show { display: block; }
    .ac-editor-head { padding: .875rem 1.25rem; border-bottom: 1px solid var(--border, #334155); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
    .ac-file-name { font-weight: 600; font-size: 14px; color: var(--text, #f0f0f0); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 280px; }
    .ac-file-meta { font-size: 11px; color: var(--text-3, #94a3b8); }
    .ac-editor-body { padding: 1.25rem; }

    .ac-wave-wrap { position: relative; border-radius: 12px; overflow: hidden; background: #0a0e1a; margin-bottom: .75rem; cursor: crosshair; }
    #acWave { display: block; width: 100%; height: 120px; }
    .ac-playhead { position: absolute; top: 0; bottom: 0; width: 2px; background: #fff; opacity: .8; pointer-events: none; left: 0; }

    .ac-time-row { display: flex; justify-content: space-between; font-size: 11px; color: var(--text-3, #94a3b8); font-variant-numeric: tabular-nums; margin-bottom: 1rem; padding: 0 2px; }

    .ac-sliders { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }
    .ac-slider-row { display: flex; align-items: center; gap: 10px; }
    .ac-slider-label { font-size: 11px; font-weight: 700; color: var(--text-3, #94a3b8); width: 36px; flex-shrink: 0; }
    .ac-slider-row input[type=range] { flex: 1; accent-color: var(--ac); cursor: pointer; height: 4px; }
    .ac-slider-val { font-size: 12px; font-variant-numeric: tabular-nums; color: var(--text, #f0f0f0); width: 52px; text-align: right; flex-shrink: 0; }

    .ac-sel-info { display: flex; align-items: center; justify-content: center; gap: 16px; background: var(--ac-lt); border: 1px solid rgba(56,189,248,.25); border-radius: 12px; padding: .6rem 1rem; margin-bottom: 1rem; font-size: 13px; }
    .ac-sel-item { display: flex; flex-direction: column; align-items: center; gap: 2px; }
    .ac-sel-lbl { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: var(--text-3, #94a3b8); }
    .ac-sel-val { font-weight: 700; font-variant-numeric: tabular-nums; color: var(--ac); }

    .ac-controls { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 1.1rem; }
    .ac-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: .15s; font-family: inherit; }
    .ac-btn:disabled { opacity: .45; cursor: not-allowed; }
    .ac-btn-play  { background: var(--card-bg, #1e293b); border: 1px solid var(--border, #334155); color: var(--text, #f0f0f0); }
    .ac-btn-play:not(:disabled):hover  { border-color: var(--ac); color: var(--ac); }
    .ac-btn-prev  { background: var(--ac-lt); border: 1px solid rgba(56,189,248,.3); color: var(--ac); }
    .ac-btn-prev:not(:disabled):hover  { background: rgba(56,189,248,.2); }
    .ac-btn-stop  { background: var(--card-bg, #1e293b); border: 1px solid var(--border, #334155); color: var(--text-3, #94a3b8); }
    .ac-btn-stop:not(:disabled):hover  { border-color: var(--rd); color: var(--rd); }
    .ac-btn-cut   { background: linear-gradient(135deg, var(--ac), var(--ac-dk)); color: #fff; box-shadow: 0 4px 14px rgba(56,189,248,.3); flex: 1; justify-content: center; }
    .ac-btn-cut:not(:disabled):hover   { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(56,189,248,.4); }

    .ac-result { background: rgba(34,197,94,.06); border: 1px solid rgba(34,197,94,.3); border-radius: 14px; padding: 1rem 1.25rem; display: none; }
    .ac-result.show { display: block; }
    .ac-result-head { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: var(--green); margin-bottom: .75rem; }
    .ac-result audio { width: 100%; border-radius: 8px; margin-bottom: .75rem; }
    .ac-dl { display: inline-flex; align-items: center; gap: 8px; background: var(--green); color: #fff; padding: 10px 22px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; transition: .15s; }
    .ac-dl:hover { opacity: .88; transform: translateY(-1px); }
    .ac-reset { display: inline-flex; align-items: center; gap: 6px; background: transparent; border: 1px solid var(--border, #334155); color: var(--text-3, #94a3b8); padding: 10px 18px; border-radius: 10px; font-size: 13px; cursor: pointer; margin-left: 8px; font-family: inherit; }
    .ac-reset:hover { border-color: var(--text-3); color: var(--text); }

    .ac-status { font-size: 12px; color: var(--text-3, #94a3b8); margin-top: 8px; min-height: 16px; }
    .ac-status.err { color: var(--rd); }

    .ac-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap: 12px; margin-top: 2.5rem; }
    .ac-info-card { background: var(--card-bg, #0f172a); border: 1px solid var(--border, #334155); border-radius: 16px; padding: 1.1rem; }
    .ac-info-icon { font-size: 1.5rem; margin-bottom: .5rem; }
    .ac-info-title { font-weight: 700; font-size: 13px; color: var(--text, #f0f0f0); margin-bottom: .35rem; }
    .ac-info-body { font-size: 12px; color: var(--text-3, #94a3b8); line-height: 1.6; }

    .ac-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-3, #94a3b8); text-decoration: none; margin-bottom: 1.5rem; }
    .ac-back:hover { color: var(--text); }

    @media(max-width:480px) {
        .ac-hero h1 { font-size: 1.4rem; }
        .ac-controls { flex-direction: column; }
        .ac-btn-cut { flex: none; }
    }
</style>
@endpush

@section('content')
<div class="ac-page">

    <a href="{{ route('home') }}" class="ac-back">← Kembali ke Beranda</a>

    <div class="ac-hero">
        <div class="ac-hero-badge">✂️ Tool Gratis</div>
        <h1>Pemotong Lagu Online</h1>
        <p>Potong bagian lagu yang kamu mau langsung di browser &mdash; tanpa upload ke server, tanpa install aplikasi, dan tanpa biaya.</p>
    </div>

    <div class="ac-drop" id="acDrop" onclick="document.getElementById('acFileInput').click()">
        <div class="ac-drop-icon">🎵</div>
        <div class="ac-drop-text">Seret file audio ke sini atau klik untuk pilih</div>
        <div class="ac-drop-sub">MP3 · WAV · OGG · FLAC · AAC · M4A &nbsp;|&nbsp; Maks 300 MB &nbsp;|&nbsp; Tidak diunggah ke server</div>
    </div>
    <input type="file" id="acFileInput" accept="audio/*">

    <div class="ac-editor" id="acEditor">
        <div class="ac-editor-head">
            <div>
                <div class="ac-file-name" id="acFileName">—</div>
                <div class="ac-file-meta" id="acFileMeta">—</div>
            </div>
            <button class="ac-btn ac-btn-stop" onclick="acChangeFile()" style="font-size:12px;padding:6px 12px;">🔄 Ganti File</button>
        </div>
        <div class="ac-editor-body">

            <div class="ac-wave-wrap" id="acWaveWrap">
                <canvas id="acWave"></canvas>
                <div class="ac-playhead" id="acPlayhead" style="display:none;"></div>
            </div>
            <div class="ac-time-row">
                <span>0:00</span>
                <span id="acDurLabel">0:00</span>
            </div>

            <div class="ac-sliders">
                <div class="ac-slider-row">
                    <span class="ac-slider-label" style="color:#22d3ee;">Mulai</span>
                    <input type="range" id="acStart" min="0" max="100" step="0.01" value="0">
                    <span class="ac-slider-val" id="acStartVal" style="color:#22d3ee;">0:00</span>
                </div>
                <div class="ac-slider-row">
                    <span class="ac-slider-label" style="color:#f59e0b;">Akhir</span>
                    <input type="range" id="acEnd" min="0" max="100" step="0.01" value="100">
                    <span class="ac-slider-val" id="acEndVal" style="color:#f59e0b;">0:00</span>
                </div>
            </div>

            <div class="ac-sel-info">
                <div class="ac-sel-item">
                    <span class="ac-sel-lbl">Mulai</span>
                    <span class="ac-sel-val" id="acSelStart">0:00</span>
                </div>
                <div style="color:var(--text-3,#94a3b8);font-size:18px;">→</div>
                <div class="ac-sel-item">
                    <span class="ac-sel-lbl">Akhir</span>
                    <span class="ac-sel-val" id="acSelEnd">0:00</span>
                </div>
                <div style="color:var(--text-3,#94a3b8);font-size:18px;">|</div>
                <div class="ac-sel-item">
                    <span class="ac-sel-lbl">Durasi Pilihan</span>
                    <span class="ac-sel-val" id="acSelDur">0:00</span>
                </div>
            </div>

            <div class="ac-controls">
                <button class="ac-btn ac-btn-play" id="acBtnPlay" onclick="acPlay()">▶ Play Semua</button>
                <button class="ac-btn ac-btn-prev" id="acBtnPrev" onclick="acPreview()">▶ Preview Pilihan</button>
                <button class="ac-btn ac-btn-stop" id="acBtnStop" onclick="acStop()">⏹ Stop</button>
                <button class="ac-btn ac-btn-cut" id="acBtnCut" onclick="acCut()">✂️ Potong &amp; Unduh</button>
            </div>
            <div class="ac-status" id="acStatus"></div>

            <div class="ac-result" id="acResult">
                <div class="ac-result-head">✅ Potongan siap diunduh</div>
                <audio id="acClipPlayer" controls></audio>
                <div>
                    <a id="acDlLink" class="ac-dl" download>⬇️ Unduh WAV</a>
                    <button class="ac-reset" onclick="acCutAgain()">✂️ Potong Lagi</button>
                </div>
            </div>

        </div>
    </div>

    <section style="margin-top:3rem;">
        <h2 style="font-family:'Space Grotesk','Sora',sans-serif;font-size:1.1rem;font-weight:700;color:var(--text,#f0f0f0);margin-bottom:1rem;">Tentang Tool Ini</h2>
        <div class="ac-info-grid">
            <div class="ac-info-card">
                <div class="ac-info-icon">🔒</div>
                <div class="ac-info-title">Privasi 100%</div>
                <div class="ac-info-body">File audio tidak pernah dikirim ke server. Semua pemrosesan terjadi di dalam browser kamu menggunakan Web Audio API.</div>
            </div>
            <div class="ac-info-card">
                <div class="ac-info-icon">⚡</div>
                <div class="ac-info-title">Cepat &amp; Langsung</div>
                <div class="ac-info-body">Tidak perlu tunggu upload. Geser slider untuk pilih bagian yang kamu mau, lalu klik Potong — selesai dalam hitungan detik.</div>
            </div>
            <div class="ac-info-card">
                <div class="ac-info-icon">🎵</div>
                <div class="ac-info-title">Format Didukung</div>
                <div class="ac-info-body">MP3, WAV, OGG, FLAC, AAC, M4A — semua format yang didukung browser. Output diunduh sebagai file WAV berkualitas tinggi.</div>
            </div>
            <div class="ac-info-card">
                <div class="ac-info-icon">🎸</div>
                <div class="ac-info-title">Cocok untuk Musisi</div>
                <div class="ac-info-body">Potong bagian intro, verse, atau chorus untuk latihan, preview, atau konten media sosial. Presisi hingga 0.01 detik.</div>
            </div>
        </div>
    </section>

    <section style="margin-top:2.5rem;">
        <h2 style="font-family:'Space Grotesk','Sora',sans-serif;font-size:1.1rem;font-weight:700;color:var(--text,#f0f0f0);margin-bottom:.75rem;">Cara Menggunakan</h2>
        <ol style="font-size:13px;color:var(--text-3,#94a3b8);line-height:2;padding-left:1.25rem;">
            <li>Klik area upload atau seret file audio dari komputer ke kotak di atas</li>
            <li>Geser slider <span style="color:#22d3ee;font-weight:700;">Mulai</span> dan <span style="color:#f59e0b;font-weight:700;">Akhir</span> untuk memilih bagian yang diinginkan</li>
            <li>Klik <b>Preview Pilihan</b> untuk mendengarkan hasil sebelum memotong</li>
            <li>Klik <b>✂️ Potong &amp; Unduh</b> — file WAV langsung terunduh</li>
        </ol>
    </section>

    <p style="text-align:center;margin-top:3rem;font-size:12px;color:var(--text-3,#94a3b8);">
        Bagian dari komunitas musik <a href="{{ route('home') }}" style="color:var(--ac);">Margonoandi Fanbase</a> &mdash;
        platform musisi Indonesia 🎸
    </p>

</div>
@endsection

@push('scripts')
<script>
var _ctx=null,_buf=null,_src=null,_startT=0,_endT=0,_dur=0,_playCtxTime=0,_playOffset=0,_playing=false,_prevStop=null,_rafId=null,_fileName='lagu',_resultUrl=null;

function fmtT(s){s=Math.max(0,s||0);var m=Math.floor(s/60),x=s%60;return m+':'+(x<10?'0':'')+x.toFixed(2);}
function fmtShort(s){s=Math.max(0,s||0);var m=Math.floor(s/60),x=Math.floor(s%60);return m+':'+(x<10?'0':'')+x;}
function setStatus(msg,err){var el=document.getElementById('acStatus');el.textContent=msg;el.className='ac-status'+(err?' err':'');}

var drop=document.getElementById('acDrop');
drop.addEventListener('dragover',function(e){e.preventDefault();drop.classList.add('drag-over');});
drop.addEventListener('dragleave',function(){drop.classList.remove('drag-over');});
drop.addEventListener('drop',function(e){e.preventDefault();drop.classList.remove('drag-over');var f=e.dataTransfer.files[0];if(f)loadAudioFile(f);});
document.getElementById('acFileInput').addEventListener('change',function(){if(this.files[0])loadAudioFile(this.files[0]);});

function loadAudioFile(file){
    var mb=(file.size/1024/1024).toFixed(1);
    if(file.size>300*1024*1024){alert('File terlalu besar (maks 300 MB).');return;}
    _fileName=file.name.replace(/\.[^.]+$/,'');
    document.getElementById('acFileName').textContent=file.name;
    document.getElementById('acFileMeta').textContent=mb+' MB · memuat…';
    setStatus('Membaca file dan menggambar waveform…');
    if(_ctx){try{_ctx.close();}catch(e){}}
    _ctx=new(window.AudioContext||window.webkitAudioContext)();
    var reader=new FileReader();
    reader.onload=function(e){
        _ctx.decodeAudioData(e.target.result,function(buffer){
            _buf=buffer;_dur=buffer.duration;_startT=0;_endT=_dur;
            var sr=buffer.sampleRate,ch=buffer.numberOfChannels;
            document.getElementById('acFileMeta').textContent=mb+' MB · '+fmtShort(_dur)+' · '+sr+' Hz · '+ch+'ch';
            document.getElementById('acDurLabel').textContent=fmtShort(_dur);
            var sl=document.getElementById('acStart'),el=document.getElementById('acEnd');
            sl.max=el.max=_dur.toFixed(2);sl.step=el.step=(_dur/1000).toFixed(4);sl.value=0;el.value=_dur.toFixed(2);
            drawWaveform();updateDisplay();
            document.getElementById('acEditor').classList.add('show');
            document.getElementById('acResult').classList.remove('show');
            setStatus('');
        },function(){setStatus('Gagal membaca file — pastikan format audio yang valid.',true);});
    };
    reader.readAsArrayBuffer(file);
}
function acChangeFile(){acStop();document.getElementById('acEditor').classList.remove('show');document.getElementById('acResult').classList.remove('show');document.getElementById('acFileInput').value='';_buf=null;_dur=0;setStatus('');}

function drawWaveform(){
    var canvas=document.getElementById('acWave'),wrap=document.getElementById('acWaveWrap');
    var W=wrap.clientWidth||680,H=120;canvas.width=W;canvas.height=H;
    var ctx=canvas.getContext('2d');ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
    if(!_buf)return;
    var data=_buf.getChannelData(0),step=Math.ceil(data.length/W);
    var sx=(_startT/_dur)*W,ex=(_endT/_dur)*W;
    ctx.fillStyle='rgba(56,189,248,0.08)';ctx.fillRect(sx,0,ex-sx,H);
    for(var i=0;i<W;i++){
        var max=0;for(var j=0;j<step;j++){var v=Math.abs(data[i*step+j]||0);if(v>max)max=v;}
        var barH=Math.max(1,max*H*.92),y=(H-barH)/2;
        ctx.fillStyle=(i>=sx&&i<=ex)?'#38bdf8':'#1e3a4a';ctx.fillRect(i,y,1,barH);
    }
    ctx.fillStyle='#22d3ee';ctx.fillRect(sx,0,2,H);ctx.beginPath();ctx.moveTo(sx,0);ctx.lineTo(sx+12,0);ctx.lineTo(sx,16);ctx.closePath();ctx.fill();
    ctx.fillStyle='#f59e0b';ctx.fillRect(ex-2,0,2,H);ctx.beginPath();ctx.moveTo(ex,0);ctx.lineTo(ex-12,0);ctx.lineTo(ex,16);ctx.closePath();ctx.fill();
}

document.getElementById('acStart').addEventListener('input',function(){_startT=parseFloat(this.value);if(_startT>=_endT-0.1){_startT=_endT-0.1;this.value=_startT.toFixed(4);}drawWaveform();updateDisplay();});
document.getElementById('acEnd').addEventListener('input',function(){_endT=parseFloat(this.value);if(_endT<=_startT+0.1){_endT=_startT+0.1;this.value=_endT.toFixed(4);}drawWaveform();updateDisplay();});
function updateDisplay(){document.getElementById('acStartVal').textContent=fmtShort(_startT);document.getElementById('acEndVal').textContent=fmtShort(_endT);document.getElementById('acSelStart').textContent=fmtT(_startT);document.getElementById('acSelEnd').textContent=fmtT(_endT);document.getElementById('acSelDur').textContent=fmtT(_endT-_startT);}

document.getElementById('acWaveWrap').addEventListener('click',function(e){
    if(!_dur)return;var rect=this.getBoundingClientRect(),t=((e.clientX-rect.left)/rect.width)*_dur;
    var ds=Math.abs(t-_startT),de=Math.abs(t-_endT);
    if(ds<de){_startT=Math.max(0,Math.min(t,_endT-0.1));document.getElementById('acStart').value=_startT.toFixed(4);}
    else{_endT=Math.max(_startT+0.1,Math.min(t,_dur));document.getElementById('acEnd').value=_endT.toFixed(4);}
    drawWaveform();updateDisplay();
});

function acPlay(){if(!_buf)return;acStop();_ctx.resume();_src=_ctx.createBufferSource();_src.buffer=_buf;_src.connect(_ctx.destination);_playOffset=0;_playCtxTime=_ctx.currentTime;_src.start(0,0);_playing=true;rafTick();}
function acPreview(){if(!_buf)return;acStop();_ctx.resume();_src=_ctx.createBufferSource();_src.buffer=_buf;_src.connect(_ctx.destination);_playOffset=_startT;_playCtxTime=_ctx.currentTime;_src.start(0,_startT,_endT-_startT);_playing=true;_prevStop=setTimeout(acStop,(_endT-_startT)*1000+200);rafTick();}
function acStop(){if(_prevStop){clearTimeout(_prevStop);_prevStop=null;}if(_rafId){cancelAnimationFrame(_rafId);_rafId=null;}if(_src){try{_src.stop();}catch(e){}_src=null;}_playing=false;document.getElementById('acPlayhead').style.display='none';}
function rafTick(){if(!_playing)return;var elapsed=_ctx.currentTime-_playCtxTime,pos=(_playOffset+elapsed)/_dur;if(pos>1){acStop();return;}var canvas=document.getElementById('acWave'),ph=document.getElementById('acPlayhead');ph.style.display='block';ph.style.left=(pos*canvas.width)+'px';_rafId=requestAnimationFrame(rafTick);}

function acCut(){
    if(!_buf)return;var sel=_endT-_startT;if(sel<0.1){setStatus('Pilihan terlalu pendek — minimal 0.1 detik.',true);return;}
    setStatus('Memproses…');document.getElementById('acBtnCut').disabled=true;
    setTimeout(function(){
        try{
            var blob=bufferToWav(_buf,_startT,_endT);
            if(_resultUrl)URL.revokeObjectURL(_resultUrl);
            _resultUrl=URL.createObjectURL(blob);
            document.getElementById('acClipPlayer').src=_resultUrl;
            var dl=document.getElementById('acDlLink');dl.href=_resultUrl;
            dl.download=_fileName+'_'+fmtShort(_startT).replace(':','m')+'s-'+fmtShort(_endT).replace(':','m')+'s.wav';
            document.getElementById('acResult').classList.add('show');setStatus('');
            document.getElementById('acResult').scrollIntoView({behavior:'smooth',block:'nearest'});
        }catch(e){setStatus('Gagal: '+(e.message||e),true);}
        finally{document.getElementById('acBtnCut').disabled=false;}
    },50);
}
function acCutAgain(){document.getElementById('acResult').classList.remove('show');}

function bufferToWav(buffer,startSec,endSec){
    var sr=buffer.sampleRate,nCh=buffer.numberOfChannels;
    var startSamp=Math.floor(startSec*sr),endSamp=Math.min(Math.ceil(endSec*sr),buffer.length),nSamp=endSamp-startSamp;
    var dataLen=nSamp*nCh*2,ab=new ArrayBuffer(44+dataLen),v=new DataView(ab);
    function ws(off,str){for(var i=0;i<str.length;i++)v.setUint8(off+i,str.charCodeAt(i));}
    ws(0,'RIFF');v.setUint32(4,36+dataLen,true);ws(8,'WAVE');ws(12,'fmt ');
    v.setUint32(16,16,true);v.setUint16(20,1,true);v.setUint16(22,nCh,true);
    v.setUint32(24,sr,true);v.setUint32(28,sr*nCh*2,true);v.setUint16(32,nCh*2,true);v.setUint16(34,16,true);
    ws(36,'data');v.setUint32(40,dataLen,true);
    var offset=44;
    for(var i=0;i<nSamp;i++)for(var ch=0;ch<nCh;ch++){var s=Math.max(-1,Math.min(1,buffer.getChannelData(ch)[startSamp+i]));v.setInt16(offset,s<0?s*0x8000:s*0x7FFF,true);offset+=2;}
    return new Blob([ab],{type:'audio/wav'});
}
window.addEventListener('resize',function(){if(_buf)drawWaveform();});
</script>
@endpush
BLADE_EOF;

$steps[] = ['label' => 'Buat resources/views/tools/audio-cutter.blade.php']
         + patchWrite($base . '/resources/views/tools/audio-cutter.blade.php', $audioCutter);

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 3 — routes/web.php  (use + route)
// ═════════════════════════════════════════════════════════════════════════════
$steps[] = ['label' => 'Patch routes/web.php — use ToolController']
         + patchReplace(
             $base . '/routes/web.php',
             "use App\Http\Controllers\MusicianController;\n",
             "use App\Http\Controllers\MusicianController;\nuse App\Http\Controllers\ToolController;\n",
             'use App\Http\Controllers\ToolController;'
           );

$steps[] = ['label' => 'Patch routes/web.php — tambah route potong-lagu']
         + patchReplace(
             $base . '/routes/web.php',
             "// Google Auth\n",
             "// Tools publik — tanpa login\nRoute::get('/tools/potong-lagu', [ToolController::class, 'audioCutter'])->name('tools.potong-lagu');\n\n// Google Auth\n",
             "Route::get('/tools/potong-lagu'"
           );

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 4 — home.blade.php  (card Pemotong Lagu setelah CTA)
// ═════════════════════════════════════════════════════════════════════════════
$homePatchSearch = <<<'SEARCH'
    <p class="fb-promo-note">@auth Kamu sudah di dalam &mdash; ayo lanjut berkarya. @else Cukup login pakai Google &mdash; Gratis &amp; Aman. @endauth</p>

    <hr style="border:none;border-top:1px solid var(--border);margin:2.5rem 0;"></SEARCH;

$homePatchReplace = <<<'REPLACE'
    <p class="fb-promo-note">@auth Kamu sudah di dalam &mdash; ayo lanjut berkarya. @else Cukup login pakai Google &mdash; Gratis &amp; Aman. @endauth</p>

    {{-- TOOLS GRATIS --}}
    <a href="{{ route('tools.potong-lagu') }}"
       onclick="gtag && gtag('event', 'cta_click', {event_category:'tools', button:'potong_lagu_landing'})"
       style="display:flex;align-items:center;gap:14px;background:var(--card-bg,rgba(15,23,42,0.6));border:1px solid var(--border);border-radius:16px;padding:1rem 1.25rem;text-decoration:none;margin-top:1.25rem;transition:.2s;">
        <div style="font-size:2rem;flex-shrink:0;">✂️</div>
        <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:14px;color:var(--text,#f0f0f0);">Pemotong Lagu Online — Gratis</div>
            <div style="font-size:12px;color:var(--text-3,#94a3b8);margin-top:2px;">Potong MP3, WAV, OGG langsung di browser. Tanpa install, tanpa upload ke server.</div>
        </div>
        <div style="font-size:12px;font-weight:700;color:var(--accent,#38bdf8);white-space:nowrap;">Coba →</div>
    </a>

    <hr style="border:none;border-top:1px solid var(--border);margin:2.5rem 0;"></REPLACE;

$steps[] = ['label' => 'Patch home.blade.php — tambah card Pemotong Lagu']
         + patchReplace(
             $base . '/resources/views/home.blade.php',
             $homePatchSearch,
             $homePatchReplace,
             "route('tools.potong-lagu')"
           );

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 5 — layouts/fanbase.blade.php  (nav item Potong Lagu)
// ═════════════════════════════════════════════════════════════════════════════
$fanbasePatchSearch = <<<'SEARCH'
            <div class="fb-nav-divider"></div>
            <a href="{{ route('home') }}" class="fb-nav-item">
                <span class="fb-nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span>Beranda</span>
            </a>SEARCH;

$fanbasePatchReplace = <<<'REPLACE'
            <div class="fb-nav-divider"></div>
            <a href="{{ route('tools.potong-lagu') }}"
               class="fb-nav-item {{ request()->routeIs('tools.*') ? 'active' : '' }}">
                <span class="fb-nav-icon">✂️</span>
                <span>Potong Lagu</span>
            </a>
            <a href="{{ route('home') }}" class="fb-nav-item">
                <span class="fb-nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span>Beranda</span>
            </a>REPLACE;

$steps[] = ['label' => 'Patch layouts/fanbase.blade.php — tambah nav Potong Lagu']
         + patchReplace(
             $base . '/resources/views/layouts/fanbase.blade.php',
             $fanbasePatchSearch,
             $fanbasePatchReplace,
             "route('tools.potong-lagu')"
           );

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 6 — admin/audio-cut.blade.php  (CSS: region-track → region-wave-wrap)
// ═════════════════════════════════════════════════════════════════════════════
$steps[] = ['label' => 'Patch admin/audio-cut.blade.php — CSS region selector']
         + patchReplace(
             $base . '/resources/views/admin/audio-cut.blade.php',
             "    /* Region selector */\n    .region-track { position:relative; height:52px; background:linear-gradient(var(--bg-3),var(--bg-3)); border:1px solid var(--border); border-radius:8px; margin:12px 0 4px; overflow:hidden; cursor:pointer; }\n    .region-sel { position:absolute; top:0; bottom:0; background:rgba(99,102,241,0.22); border-left:2px solid var(--accent); border-right:2px solid var(--accent); }\n    .region-play { position:absolute; top:0; bottom:0; width:2px; background:var(--text); opacity:0.7; left:0; }\n    .region-time { display:flex; justify-content:space-between; font-size:11px; color:var(--text-3); font-variant-numeric:tabular-nums; }\n    .range-pair { margin-top:8px; }\n    .range-pair input[type=range] { width:100%; accent-color:var(--accent); margin:2px 0; }",
             "    /* Waveform + region */\n    .region-wave-wrap { position:relative; border-radius:8px; overflow:hidden; background:#0a0e1a; margin:12px 0 4px; cursor:crosshair; }\n    #adminWave { display:block; width:100%; height:80px; }\n    .region-play { position:absolute; top:0; bottom:0; width:2px; background:#fff; opacity:.7; left:0; pointer-events:none; }\n    .region-time { display:flex; justify-content:space-between; font-size:11px; color:var(--text-3); font-variant-numeric:tabular-nums; }\n    .range-pair { margin-top:8px; }\n    .range-pair input[type=range] { width:100%; accent-color:var(--accent); margin:2px 0; }",
             'region-wave-wrap'
           );

// ── HTML: div region-track → canvas ──────────────────────────────────────────
$steps[] = ['label' => 'Patch admin/audio-cut.blade.php — HTML waveform canvas']
         + patchReplace(
             $base . '/resources/views/admin/audio-cut.blade.php',
             "            <div class=\"region-track\" id=\"regionTrack\">\n                <div class=\"region-sel\" id=\"regionSel\"></div>\n                <div class=\"region-play\" id=\"regionPlay\"></div>\n            </div>",
             "            <div class=\"region-wave-wrap\" id=\"regionTrack\">\n                <canvas id=\"adminWave\"></canvas>\n                <div class=\"region-play\" id=\"regionPlay\" style=\"display:none;\"></div>\n            </div>",
             'id="adminWave"'
           );

// ── Script: ganti blok ffmpeg dengan Web Audio API ────────────────────────────
$adminOldScript = '<script src="{{ asset(\'ffmpeg/ffmpeg.js\') }}"></script>';

$adminNewScript = <<<'NEWSCRIPT'
<script>
// ── Admin Audio Cutter — Web Audio API (no ffmpeg needed) ──
var player    = document.getElementById('player');
var _ctx = null, _buf = null, _src = null;
var srcUrl = null, srcName = 'lagu', duration = 0;
var _startT = 0, _endT = 0, _playing = false, _prevStop = null, _raf = null;
var lastClipBlob = null, lastClipUrl = null;

function fmt(s){ s=Math.max(0,s||0); var m=Math.floor(s/60),x=Math.floor(s%60); return m+':'+(x<10?'0':'')+x; }
function getExt(n){ var m=(n||'').match(/\.([a-z0-9]+)(?:\?|$)/i); return m?m[1].toLowerCase():'mp3'; }
function setStatus(html){ document.getElementById('status').innerHTML = html||''; }

document.getElementById('songSelect').addEventListener('change', function(){
    if (!this.value) return;
    var opt = this.options[this.selectedIndex];
    document.getElementById('fileInput').value = '';
    fetchAndLoad(this.value, opt.getAttribute('data-title')||'lagu');
});
document.getElementById('fileInput').addEventListener('change', function(){
    var f=this.files[0]; if(!f) return;
    document.getElementById('songSelect').value='';
    readFileAndLoad(f);
});

function readFileAndLoad(file){
    srcName = file.name.replace(/\.[^.]+$/,'');
    setStatus('<span class="spinner"></span> Membaca file…');
    var reader=new FileReader();
    reader.onload=function(e){ decodeBuffer(e.target.result); };
    reader.readAsArrayBuffer(file);
}
async function fetchAndLoad(url, name){
    srcUrl=url; srcName=name;
    setStatus('<span class="spinner"></span> Mengambil file dari server…');
    try {
        var res = await fetch(url);
        var ab  = await res.arrayBuffer();
        decodeBuffer(ab);
    } catch(e){ setStatus('⚠️ Gagal mengambil file: '+e.message); }
}

function decodeBuffer(ab){
    if(_ctx){ try{_ctx.close();}catch(e){} }
    _ctx = new (window.AudioContext||window.webkitAudioContext)();
    setStatus('<span class="spinner"></span> Mendekode audio…');
    _ctx.decodeAudioData(ab, function(buf){
        _buf=buf; duration=buf.duration;
        _startT=0; _endT=duration;
        player.src='';
        document.getElementById('srcInfo').textContent = '🎵 '+srcName+' · '+fmt(duration);
        document.getElementById('durLabel').textContent = fmt(duration);
        var sr=document.getElementById('startRange'), er=document.getElementById('endRange');
        sr.max=er.max=duration.toFixed(1); sr.step=er.step=(duration/1000).toFixed(4);
        sr.value=0; er.value=duration.toFixed(1);
        updateRegion(); drawAdminWave();
        document.getElementById('editArea').style.display='block';
        document.getElementById('resultWrap').style.display='none';
        setStatus('');
    }, function(){ setStatus('⚠️ Gagal mendekode — coba format lain.'); });
}

function drawAdminWave(){
    var canvas=document.getElementById('adminWave');
    var wrap=document.getElementById('regionTrack');
    var W=wrap.clientWidth||560, H=80;
    canvas.width=W; canvas.height=H;
    var ctx=canvas.getContext('2d');
    ctx.fillStyle='#0a0e1a'; ctx.fillRect(0,0,W,H);
    if(!_buf) return;
    var data=_buf.getChannelData(0), step=Math.ceil(data.length/W);
    var sx2=(_startT/duration)*W, ex2=(_endT/duration)*W;
    ctx.fillStyle='rgba(99,102,241,.1)'; ctx.fillRect(sx2,0,ex2-sx2,H);
    for(var i=0;i<W;i++){
        var max=0;
        for(var j=0;j<step;j++){ var v=Math.abs(data[i*step+j]||0); if(v>max)max=v; }
        var bH=Math.max(1,max*H*.9), y=(H-bH)/2;
        ctx.fillStyle=(i>=sx2&&i<=ex2)?'#6366f1':'#1e293b';
        ctx.fillRect(i,y,1,bH);
    }
    ctx.fillStyle='#818cf8'; ctx.fillRect(sx2,0,2,H);
    ctx.fillStyle='#f59e0b'; ctx.fillRect(ex2-2,0,2,H);
}

var startRange=document.getElementById('startRange'), endRange=document.getElementById('endRange');
startRange.addEventListener('input', function(){
    _startT=parseFloat(this.value);
    if(_startT>=_endT-0.1){_startT=_endT-0.1;this.value=_startT.toFixed(4);}
    updateRegion(); drawAdminWave();
});
endRange.addEventListener('input', function(){
    _endT=parseFloat(this.value);
    if(_endT<=_startT+0.1){_endT=_startT+0.1;this.value=_endT.toFixed(4);}
    updateRegion(); drawAdminWave();
});
function getStart(){ return _startT; }
function getEnd(){ return _endT; }
function updateRegion(){
    document.getElementById('segLabel').textContent='🟦 '+fmt(_startT)+' – '+fmt(_endT)+' · durasi '+fmt(_endT-_startT);
}
function setEdge(which){
    if(!duration) return;
    var t = _playCtxTime ? (_ctx.currentTime - _playCtxTime + _startT) : 0;
    if(which==='start'){ _startT=Math.max(0,Math.min(t,_endT-0.1)); startRange.value=_startT.toFixed(4); }
    else { _endT=Math.max(_startT+0.1,Math.min(t,duration)); endRange.value=_endT.toFixed(4); }
    updateRegion(); drawAdminWave();
}
document.getElementById('regionTrack').addEventListener('click', function(ev){
    if(!duration) return;
    var rect=this.getBoundingClientRect();
    var t=(ev.clientX-rect.left)/rect.width*duration;
    var ds=Math.abs(t-_startT), de=Math.abs(t-_endT);
    if(ds<de){ _startT=Math.max(0,Math.min(t,_endT-0.1)); startRange.value=_startT.toFixed(4); }
    else { _endT=Math.max(_startT+0.1,Math.min(t,duration)); endRange.value=_endT.toFixed(4); }
    updateRegion(); drawAdminWave();
});

var _playCtxTime=0, _playOffset=0;
function previewRegion(){
    if(!_buf) return;
    _stopSrc();
    _ctx.resume();
    _src=_ctx.createBufferSource(); _src.buffer=_buf; _src.connect(_ctx.destination);
    _playOffset=_startT; _playCtxTime=_ctx.currentTime;
    _src.start(0,_startT,_endT-_startT);
    _playing=true; _rafTick();
    _prevStop=setTimeout(_stopSrc, (_endT-_startT)*1000+300);
}
function _stopSrc(){
    if(_prevStop){clearTimeout(_prevStop);_prevStop=null;}
    if(_raf){cancelAnimationFrame(_raf);_raf=null;}
    if(_src){try{_src.stop();}catch(e){}_src=null;}
    _playing=false;
    document.getElementById('regionPlay').style.display='none';
}
function _rafTick(){
    if(!_playing) return;
    var elapsed=_ctx.currentTime-_playCtxTime;
    var pos=(_playOffset+elapsed)/duration;
    if(pos>1){_stopSrc();return;}
    var canvas=document.getElementById('adminWave');
    var ph=document.getElementById('regionPlay');
    ph.style.display='block'; ph.style.left=(pos*canvas.width)+'px';
    _raf=requestAnimationFrame(_rafTick);
}

function doCut(){
    if(!_buf){alert('Pilih lagu dulu.');return;}
    var s=_startT, dur=_endT-s;
    if(dur<0.1){alert('Bagian terlalu pendek.');return;}
    var cut=document.getElementById('cutBtn');
    cut.disabled=true; setStatus('<span class="spinner"></span> Memotong…');
    setTimeout(function(){
        try{
            var blob=_wavEncode(_buf,s,_endT);
            if(lastClipUrl) URL.revokeObjectURL(lastClipUrl);
            lastClipBlob=blob; lastClipUrl=URL.createObjectURL(blob);
            document.getElementById('clipPlayer').src=lastClipUrl;
            var dl=document.getElementById('downloadBtn');
            dl.href=lastClipUrl; dl.download=srcName+'_'+fmt(s).replace(':','m')+'-'+fmt(_endT).replace(':','m')+'.wav';
            document.getElementById('clipName').value=srcName+' ('+fmt(s)+'–'+fmt(_endT)+')';
            document.getElementById('resultWrap').style.display='block';
            setStatus('✓ Potongan jadi ('+fmt(dur)+'). Simpan/unduh, atau geser slider & potong part lain.');
        }catch(e){ setStatus('⚠️ Gagal: '+(e.message||e)); }
        finally{ cut.disabled=false; }
    },50);
}

function _wavEncode(buffer,s,e){
    var sr=buffer.sampleRate, nCh=buffer.numberOfChannels;
    var ss=Math.floor(s*sr), es=Math.min(Math.ceil(e*sr),buffer.length), n=es-ss;
    var ab=new ArrayBuffer(44+n*nCh*2), v=new DataView(ab);
    function ws(off,str){for(var i=0;i<str.length;i++)v.setUint8(off+i,str.charCodeAt(i));}
    ws(0,'RIFF');v.setUint32(4,36+n*nCh*2,true);ws(8,'WAVE');ws(12,'fmt ');
    v.setUint32(16,16,true);v.setUint16(20,1,true);v.setUint16(22,nCh,true);
    v.setUint32(24,sr,true);v.setUint32(28,sr*nCh*2,true);v.setUint16(32,nCh*2,true);v.setUint16(34,16,true);
    ws(36,'data');v.setUint32(40,n*nCh*2,true);
    var off=44;
    for(var i=0;i<n;i++) for(var ch=0;ch<nCh;ch++){
        var x=Math.max(-1,Math.min(1,buffer.getChannelData(ch)[ss+i]));
        v.setInt16(off,x<0?x*0x8000:x*0x7FFF,true); off+=2;
    }
    return new Blob([ab],{type:'audio/wav'});
}

function idbOpen(){return new Promise(function(res,rej){var r=indexedDB.open('mafAudioClips',1);r.onupgradeneeded=function(){r.result.createObjectStore('clips',{keyPath:'id',autoIncrement:true});};r.onsuccess=function(){res(r.result);};r.onerror=function(){rej(r.error);};});}
async function idbAll(){var db=await idbOpen();return new Promise(function(res){var t=db.transaction('clips').objectStore('clips').getAll();t.onsuccess=function(){res(t.result||[]);};t.onerror=function(){res([]);};});}
async function idbAdd(rec){var db=await idbOpen();return new Promise(function(res){var t=db.transaction('clips','readwrite').objectStore('clips').add(rec);t.onsuccess=function(){res(t.result);};});}
async function idbDel(id){var db=await idbOpen();return new Promise(function(res){db.transaction('clips','readwrite').objectStore('clips').delete(id).onsuccess=function(){res();};});}

async function saveClip(){
    if(!lastClipBlob){alert('Belum ada potongan.');return;}
    var name=(document.getElementById('clipName').value||'Potongan').trim();
    await idbAdd({name:name,ext:'wav',blob:lastClipBlob,size:lastClipBlob.size,createdAt:Date.now()});
    setStatus('✓ Tersimpan: '+name+'. Bisa langsung potong part lain.');
    renderClips();
}
async function renderClips(){
    var list=document.getElementById('clipList'), all=await idbAll();
    if(!all.length){list.innerHTML='<p class="muted">Belum ada potongan tersimpan.</p>';return;}
    list.innerHTML='';
    all.sort(function(a,b){return b.createdAt-a.createdAt;}).forEach(function(c){
        var url=URL.createObjectURL(c.blob), kb=Math.round(c.size/1024);
        var div=document.createElement('div'); div.className='clip-item';
        div.innerHTML='<div style="flex:1;min-width:160px;"><div class="clip-name">'+(c.name||'Potongan').replace(/</g,'&lt;')+'</div><div class="clip-meta">.'+(c.ext||'wav')+' · '+kb+' KB</div><audio class="mini" controls src="'+url+'"></audio></div><a class="btn btn-accent btn-sm" href="'+url+'" download="'+(c.name||'potongan')+'.'+(c.ext||'wav')+'">⬇️</a><button class="btn-del" data-id="'+c.id+'">Hapus</button>';
        div.querySelector('.btn-del').addEventListener('click',async function(){if(!confirm('Hapus?'))return;await idbDel(c.id);renderClips();});
        list.appendChild(div);
    });
}
renderClips();
window.addEventListener('resize',function(){if(_buf)drawAdminWave();});
</script>
NEWSCRIPT;

$steps[] = ['label' => 'Patch admin/audio-cut.blade.php — ganti ffmpeg script ke Web Audio API']
         + patchReplace(
             $base . '/resources/views/admin/audio-cut.blade.php',
             $adminOldScript,
             $adminNewScript,
             'Web Audio API (no ffmpeg needed)'
           );

// ═════════════════════════════════════════════════════════════════════════════
//  PATCH 7 — Artisan cache clear
// ═════════════════════════════════════════════════════════════════════════════
$php    = PHP_BINARY ?: 'php';
$art    = escapeshellarg($base . '/artisan');
$artCmds = ['config:clear', 'route:clear', 'view:clear', 'cache:clear'];
foreach ($artCmds as $cmd) {
    $out = shell_exec(escapeshellarg($php) . ' ' . $art . ' ' . $cmd . ' 2>&1');
    $steps[] = [
        'label' => 'php artisan ' . $cmd,
        'ok'    => true,
        'msg'   => trim($out ?: 'OK'),
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
//  Output HTML
// ─────────────────────────────────────────────────────────────────────────────
$allOk = array_reduce($steps, fn($c, $s) => $c && $s['ok'], true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Patch — Pemotong Lagu Online</title>
<style>
*{box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#0b1520;color:#e8f4fa;padding:2rem;max-width:820px;margin:0 auto}
h1{color:#38bdf8;margin-bottom:.2rem;font-size:1.2rem}
.subtitle{color:#7A9DB0;font-size:12px;margin-bottom:1.75rem}
.step{margin-bottom:.6rem;border:1px solid rgba(56,189,248,.15);border-radius:10px;overflow:hidden;display:flex;align-items:stretch}
.step-left{padding:.6rem 1rem;min-width:54px;display:flex;align-items:center;justify-content:center;font-size:1.1rem}
.step-left.ok{background:rgba(34,197,94,.12)}
.step-left.err{background:rgba(239,68,68,.12)}
.step-right{padding:.55rem 1rem;flex:1;border-left:1px solid rgba(56,189,248,.1)}
.step-label{font-size:13px;font-weight:600;color:#cbd5e1}
.step-msg{font-size:12px;color:#7A9DB0;margin-top:2px;font-family:monospace;word-break:break-all}
.step-msg.err{color:#f87171}
.banner{margin-top:1.75rem;padding:.9rem 1.25rem;border-radius:12px;font-size:14px;font-weight:600}
.banner.ok{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.banner.err{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);color:#f87171}
.note{margin-top:1rem;font-size:12px;color:#7A9DB0;background:rgba(250,204,21,.06);border:1px solid rgba(250,204,21,.25);border-radius:8px;padding:.75rem 1rem}
</style>
</head>
<body>
<h1>✂️ Patch — Pemotong Lagu Online</h1>
<div class="subtitle">feat: public tool + fix admin audio-cut (ffmpeg → Web Audio API)</div>

<?php foreach ($steps as $s): ?>
<div class="step">
    <div class="step-left <?= $s['ok'] ? 'ok' : 'err' ?>"><?= $s['ok'] ? '✅' : '❌' ?></div>
    <div class="step-right">
        <div class="step-label"><?= htmlspecialchars($s['label']) ?></div>
        <div class="step-msg <?= $s['ok'] ? '' : 'err' ?>"><?= htmlspecialchars($s['msg']) ?></div>
    </div>
</div>
<?php endforeach ?>

<div class="banner <?= $allOk ? 'ok' : 'err' ?>">
    <?= $allOk ? '✅ Semua patch berhasil! Fitur Pemotong Lagu Online sudah aktif.' : '⚠️ Ada error — cek baris merah di atas.' ?>
</div>

<div class="note">
    ⚠️ <strong>Penting:</strong> Segera hapus file <code>public/run_patch.php</code> dari hosting setelah patch ini berhasil dijalankan.
    <br>Akses tool baru: <a href="/tools/potong-lagu" style="color:#38bdf8;">/tools/potong-lagu</a>
</div>
</body>
</html>
