@extends('layouts.admin')

@push('styles')
<style>
    .ai-header { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--border); }
    .ai-header h2 { font-size:1rem; font-weight:500; color:var(--text); }
    .ai-header p { font-size:12px; color:var(--text-3); margin-top:2px; }
    .btn-back { font-size:12px; color:var(--text-2); text-decoration:none; border:1px solid var(--border); padding:6px 14px; border-radius:8px; }
    .btn-back:hover { color:var(--text); border-color:var(--text-3); }
    .alert-success { background:#0d2e1a; color:#4ade80; border:1px solid #166534; padding:10px 16px; border-radius:8px; margin-bottom:1.25rem; font-size:13px; }

    .card { background:var(--bg-2); border:1px solid var(--border); border-radius:12px; margin-bottom:1.25rem; overflow:hidden; }
    .card-head {
        display:flex; justify-content:space-between; align-items:center; gap:10px;
        padding:0.85rem 1.1rem; border-bottom:1px solid var(--border);
        font-size:13px; color:var(--text); font-weight:600;
    }
    .card-head .ch-title { display:flex; align-items:center; gap:8px; }
    .card-head .ch-sub { font-size:11px; color:var(--text-3); font-weight:400; }
    .card-body { padding:0.6rem 1.1rem 1.1rem; }

    .fg { display:flex; flex-direction:column; gap:5px; margin-bottom:12px; }
    .fg label { font-size:11px; color:var(--text-3); text-transform:uppercase; letter-spacing:0.05em; }
    .fi { background:var(--bg-3); border:1px solid var(--border); border-radius:8px; color:var(--text); font-size:13px; padding:9px 11px; outline:none; font-family:inherit; width:100%; }
    .fi:focus { border-color:var(--text-3); }
    .row2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .btn { padding:9px 18px; border-radius:8px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:0.2s; }
    .btn-primary { background:var(--text); color:var(--bg); }
    .btn-primary:hover { filter:brightness(0.88); }
    .btn-add { background:var(--accent-dim); color:var(--accent); border:none; border-radius:8px; padding:6px 13px; font-size:12px; font-weight:600; cursor:pointer; }
    .btn-add:hover { filter:brightness(1.1); }

    .prov-item { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid var(--border-2); flex-wrap:wrap; }
    .prov-item:last-child { border-bottom:none; }
    .prov-name { font-size:13px; color:var(--text); font-weight:500; }
    .prov-meta { font-size:11px; color:var(--text-3); margin-top:2px; }
    .prov-badge { font-size:10px; padding:2px 7px; border-radius:20px; background:var(--bg-3); color:var(--text-3); border:1px solid var(--border); }
    .prov-key-ok { color:#4ade80; } .prov-key-no { color:#f87171; }
    .prov-actions { display:flex; gap:6px; align-items:center; }
    .btn-edit { background:transparent; border:1px solid var(--border); color:var(--text-2); border-radius:6px; padding:4px 11px; font-size:11px; cursor:pointer; }
    .btn-edit:hover { border-color:var(--text-3); color:var(--text); }
    .btn-del { background:transparent; border:1px solid var(--border); color:var(--text-3); border-radius:6px; padding:4px 10px; font-size:11px; cursor:pointer; }
    .btn-del:hover { border-color:#ef4444; color:#ef4444; }
    .empty-row { font-size:12px; color:var(--text-3); padding:10px 0; }
    .soon { opacity:0.6; }
    .soon-badge { font-size:10px; padding:2px 8px; border-radius:20px; background:var(--bg-3); color:var(--text-3); border:1px solid var(--border); }

    /* ===== MODAL POPUP ===== */
    .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.55); display:none; align-items:flex-start; justify-content:center; z-index:1000; padding:48px 16px; overflow-y:auto; }
    .modal-overlay.open { display:flex; }
    .modal { background:var(--bg-2); border:1px solid var(--border); border-radius:12px; width:100%; max-width:460px; box-shadow:0 20px 60px rgba(0,0,0,0.4); }
    .modal-head { display:flex; justify-content:space-between; align-items:center; padding:14px 18px; border-bottom:1px solid var(--border); font-size:13px; font-weight:600; color:var(--text); }
    .modal-close { background:none; border:none; color:var(--text-3); font-size:22px; cursor:pointer; line-height:1; padding:0 4px; }
    .modal-close:hover { color:var(--text); }
    .modal-body { padding:18px; }

    @media (max-width:600px){ .row2{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')

@php
    $textProviders  = $providers->filter(fn($p) => ($p->kind ?? 'text') === 'text')->values();
    $imageProviders = $providers->filter(fn($p) => ($p->kind ?? 'text') === 'image')->values();
    $ttsProviders   = $providers->filter(fn($p) => ($p->kind ?? 'text') === 'tts')->values();
@endphp

<div class="ai-header">
    <div>
        <h2>⚙️ Pengaturan AI</h2>
        <p>Atur provider per jenis (teks / gambar / suara) + penyimpanan — sekali atur, dipakai di AI Agent</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.ai-agent') }}" class="btn-back">← AI Agent</a>
        <a href="{{ route('admin.index') }}" class="btn-back">Panel Admin</a>
    </div>
</div>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

{{-- ===== TEXT GENERATOR ===== --}}
<div class="card">
    <div class="card-head">
        <span class="ch-title">📝 Text Generator <span class="ch-sub">{{ $textProviders->count() }} provider</span></span>
        <button class="btn-add" onclick="openAddText()">+ Tambah</button>
    </div>
    <div class="card-body">
        @forelse($textProviders as $prov)
        <div class="prov-item">
            <div style="flex:1;min-width:0;">
                <span class="prov-name">{{ $prov->name }}</span> <span class="prov-badge">{{ $prov->format }}</span>
                <div class="prov-meta">{{ $prov->model }} · @if($prov->api_key)<span class="prov-key-ok">● key terisi</span>@else<span class="prov-key-no">● key kosong</span>@endif</div>
            </div>
            <div class="prov-actions">
                <button class="btn-edit" onclick="openEditText(this)"
                    data-id="{{ $prov->id }}" data-name="{{ $prov->name }}" data-model="{{ $prov->model }}"
                    data-url="{{ $prov->base_url }}" data-format="{{ $prov->format }}">Edit</button>
                <form method="POST" action="{{ route('admin.ai-agent.provider.destroy', $prov->id) }}" onsubmit="return confirm('Hapus provider {{ $prov->name }}?')">
                    @csrf @method('DELETE')<button class="btn-del">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-row">Belum ada provider teks. Klik <b>+ Tambah</b> (mulai dari preset gratis Gemini / Groq).</div>
        @endforelse
    </div>
</div>

{{-- ===== IMAGE GENERATOR ===== --}}
<div class="card">
    <div class="card-head">
        <span class="ch-title">🎨 Image Generator <span class="ch-sub">{{ $imageProviders->count() }} provider</span></span>
        <button class="btn-add" onclick="openAddImage()">+ Tambah</button>
    </div>
    <div class="card-body">
        @forelse($imageProviders as $prov)
        <div class="prov-item">
            <div style="flex:1;min-width:0;">
                <span class="prov-name">{{ $prov->name }}</span> <span class="prov-badge">{{ $prov->format }}</span>
                <div class="prov-meta">{{ $prov->model ?: 'default' }}@if(in_array($prov->format, ['dalle','imagen'])) · @if($prov->api_key)<span class="prov-key-ok">● key terisi</span>@else<span class="prov-key-no">● key kosong</span>@endif @endif</div>
            </div>
            <div class="prov-actions">
                <button class="btn-edit" onclick="openEditImage(this)"
                    data-id="{{ $prov->id }}" data-name="{{ $prov->name }}" data-model="{{ $prov->model }}"
                    data-url="{{ $prov->base_url }}" data-format="{{ $prov->format }}">Edit</button>
                <form method="POST" action="{{ route('admin.ai-agent.provider.destroy', $prov->id) }}" onsubmit="return confirm('Hapus provider {{ $prov->name }}?')">
                    @csrf @method('DELETE')<button class="btn-del">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-row"><b>Pollinations</b> (gratis, tanpa key) otomatis dipakai sebagai default. Klik <b>+ Tambah</b> untuk pilihan lain (Nano Banana / DALL-E).</div>
        @endforelse
    </div>
</div>

{{-- ===== AUDIO GENERATOR (TTS) ===== --}}
<div class="card">
    <div class="card-head">
        <span class="ch-title">🔊 Audio Generator <span class="ch-sub">TTS · {{ $ttsProviders->count() }} provider</span></span>
        <button class="btn-add" onclick="openAddTts()">+ Tambah</button>
    </div>
    <div class="card-body">
        @forelse($ttsProviders as $prov)
        <div class="prov-item">
            <div style="flex:1;min-width:0;">
                <span class="prov-name">{{ $prov->name }}</span> <span class="prov-badge">{{ $prov->model }}</span>
                <div class="prov-meta">@if($prov->api_key)<span class="prov-key-ok">● key terisi</span>@else<span class="prov-key-no">● key kosong</span>@endif</div>
            </div>
            <div class="prov-actions">
                <button class="btn-edit" onclick="openEditTts(this)"
                    data-id="{{ $prov->id }}" data-name="{{ $prov->name }}" data-model="{{ $prov->model }}">Edit</button>
                <form method="POST" action="{{ route('admin.ai-agent.provider.destroy', $prov->id) }}" onsubmit="return confirm('Hapus provider {{ $prov->name }}?')">
                    @csrf @method('DELETE')<button class="btn-del">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-row">Belum ada provider suara. Klik <b>+ Tambah</b> (pakai API key Gemini). Catatan: TTS bisa butuh billing aktif.</div>
        @endforelse
    </div>
</div>

{{-- ===== VIDEO GENERATOR (placeholder Fase C) ===== --}}
<div class="card soon">
    <div class="card-head">
        <span class="ch-title">🎬 Video Generator <span class="ch-sub">rakit gambar + audio jadi video</span></span>
        <span class="soon-badge">segera (Fase C)</span>
    </div>
    <div class="card-body">
        <div class="empty-row">Penggabung gambar + potongan lagu + narasi suara menjadi video — diproses di browser (ffmpeg.wasm). Belum aktif.</div>
    </div>
</div>

{{-- ===== PENYIMPANAN (Cloudinary) ===== --}}
<div class="card">
    <div class="card-head">
        <span class="ch-title">📦 Penyimpanan Cloudinary
            @if($cloudinary['cloud'] && $cloudinary['secret_set'])<span class="prov-key-ok" style="font-size:11px;">● aktif</span>@else<span class="prov-key-no" style="font-size:11px;">● belum diatur</span>@endif
        </span>
    </div>
    <div class="card-body">
        <p style="font-size:11px;color:var(--text-3);margin:4px 0 12px;line-height:1.6;">
            Tempat simpan gambar AI (gratis 25GB). Daftar di <b>cloudinary.com</b> → Dashboard → salin Cloud Name, API Key, API Secret. DB hanya menyimpan URL — server tetap ringan.
        </p>
        <form method="POST" action="{{ route('admin.ai-agent.settings') }}">
            @csrf
            <div class="row2">
                <div class="fg"><label>Cloud Name</label><input type="text" name="cloudinary_cloud" class="fi" value="{{ $cloudinary['cloud'] }}" placeholder="dxxxxxx" autocomplete="off"></div>
                <div class="fg"><label>API Key</label><input type="text" name="cloudinary_key" class="fi" value="{{ $cloudinary['key'] }}" placeholder="1234567890" autocomplete="off"></div>
            </div>
            <div class="fg">
                <label>API Secret (terenkripsi){{ $cloudinary['secret_set'] ? ' — sudah tersimpan, isi untuk ganti' : '' }}</label>
                <input type="password" name="cloudinary_secret" class="fi" placeholder="{{ $cloudinary['secret_set'] ? '••••••••• (biarkan kosong jika tidak diganti)' : 'API secret' }}" autocomplete="off">
            </div>
            <button class="btn btn-primary" type="submit">Simpan Cloudinary</button>
        </form>
    </div>
</div>

{{-- ============ MODAL: TEXT ============ --}}
<div class="modal-overlay" id="modalText">
    <div class="modal">
        <div class="modal-head"><span id="textModalTitle">Tambah Provider Teks</span><button class="modal-close" onclick="closeModal('modalText')">&times;</button></div>
        <div class="modal-body">
            <form method="POST" id="formText" action="{{ route('admin.ai-agent.provider.store') }}">
                @csrf
                <input type="hidden" name="_method" id="mText" value="">
                <input type="hidden" name="kind" value="text">
                <div class="fg">
                    <label>Preset cepat</label>
                    <select class="fi" onchange="applyPreset(this.value)">
                        <option value="">— Pilih preset / isi manual —</option>
                        <option value="gemini">Google Gemini (gratis)</option>
                        <option value="groq">Groq — Llama 3.3 (gratis)</option>
                        <option value="openrouter">OpenRouter (ada model gratis)</option>
                        <option value="openai">OpenAI</option>
                        <option value="deepseek">DeepSeek (murah)</option>
                        <option value="claude">Claude (Anthropic)</option>
                    </select>
                </div>
                <div class="row2">
                    <div class="fg"><label>Nama</label><input type="text" name="name" id="pName" class="fi" placeholder="Gemini Flash" required></div>
                    <div class="fg"><label>Model</label><input type="text" name="model" id="pModel" class="fi" placeholder="gemini-2.0-flash" required></div>
                </div>
                <div class="fg"><label>Base URL</label><input type="text" name="base_url" id="pUrl" class="fi" placeholder="https://..." required></div>
                <div class="row2">
                    <div class="fg"><label>Format</label>
                        <select name="format" id="pFormat" class="fi">
                            <option value="openai">openai-compatible</option>
                            <option value="anthropic">anthropic</option>
                        </select>
                    </div>
                    <div class="fg"><label>API Key (terenkripsi)</label><input type="password" name="api_key" id="pKey" class="fi" placeholder="sk-... / AIza..." autocomplete="off"></div>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%;">Simpan</button>
            </form>
        </div>
    </div>
</div>

{{-- ============ MODAL: IMAGE ============ --}}
<div class="modal-overlay" id="modalImage">
    <div class="modal">
        <div class="modal-head"><span id="imageModalTitle">Tambah Provider Gambar</span><button class="modal-close" onclick="closeModal('modalImage')">&times;</button></div>
        <div class="modal-body">
            <form method="POST" id="formImage" action="{{ route('admin.ai-agent.provider.store') }}">
                @csrf
                <input type="hidden" name="_method" id="mImage" value="">
                <input type="hidden" name="kind" value="image">
                <div class="fg">
                    <label>Preset cepat</label>
                    <select class="fi" onchange="applyImgPreset(this.value)">
                        <option value="">— Pilih preset / isi manual —</option>
                        <option value="pollinations">Pollinations Flux (GRATIS, tanpa key)</option>
                        <option value="pollinations-turbo">Pollinations Turbo (gratis, cepat)</option>
                        <option value="gemini-image">Gemini 2.5 Flash Image / Nano Banana (key)</option>
                        <option value="dalle">OpenAI DALL-E 3 (butuh key)</option>
                    </select>
                </div>
                <div class="row2">
                    <div class="fg"><label>Nama</label><input type="text" name="name" id="iName" class="fi" placeholder="Pollinations Flux" required></div>
                    <div class="fg"><label>Model</label><input type="text" name="model" id="iModel" class="fi" placeholder="flux / dall-e-3 / gemini-2.5-flash-image"></div>
                </div>
                <div class="row2">
                    <div class="fg"><label>Format</label>
                        <select name="format" id="iFormat" class="fi">
                            <option value="pollinations">pollinations (gratis)</option>
                            <option value="imagen">imagen (gemini)</option>
                            <option value="dalle">dalle (openai images)</option>
                        </select>
                    </div>
                    <div class="fg"><label>Base URL (DALL-E / Gemini)</label><input type="text" name="base_url" id="iUrl" class="fi" placeholder="https://..."></div>
                </div>
                <div class="fg"><label>API Key (DALL-E / Gemini — terenkripsi)</label><input type="password" name="api_key" id="iKey" class="fi" placeholder="sk-... / AIza..." autocomplete="off"></div>
                <button class="btn btn-primary" type="submit" style="width:100%;">Simpan</button>
            </form>
        </div>
    </div>
</div>

{{-- ============ MODAL: TTS ============ --}}
<div class="modal-overlay" id="modalTts">
    <div class="modal">
        <div class="modal-head"><span id="ttsModalTitle">Tambah Provider Suara</span><button class="modal-close" onclick="closeModal('modalTts')">&times;</button></div>
        <div class="modal-body">
            <form method="POST" id="formTts" action="{{ route('admin.ai-agent.provider.store') }}">
                @csrf
                <input type="hidden" name="_method" id="mTts" value="">
                <input type="hidden" name="kind" value="tts">
                <input type="hidden" name="format" value="gemini-tts">
                <input type="hidden" name="base_url" value="https://generativelanguage.googleapis.com/v1beta">
                <div class="row2">
                    <div class="fg"><label>Nama</label><input type="text" name="name" id="tName" class="fi" placeholder="Gemini TTS" value="Gemini TTS" required></div>
                    <div class="fg"><label>Model</label>
                        <select name="model" id="tModel" class="fi">
                            <option value="gemini-2.5-flash-preview-tts">gemini-2.5-flash-preview-tts</option>
                            <option value="gemini-3.1-flash-tts-preview">gemini-3.1-flash-tts-preview</option>
                        </select>
                    </div>
                </div>
                <div class="fg"><label>API Key Gemini (terenkripsi)</label><input type="password" name="api_key" id="tKey" class="fi" placeholder="AIza..." autocomplete="off"></div>
                <button class="btn btn-primary" type="submit" style="width:100%;">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
var STORE_URL = '{{ route('admin.ai-agent.provider.store') }}';
var UPD_BASE  = '{{ url('admin/ai-agent/provider') }}';

function showModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
// klik area gelap menutup modal
document.querySelectorAll('.modal-overlay').forEach(function(o){
    o.addEventListener('click', function(e){ if (e.target === o) o.classList.remove('open'); });
});

/* ===== TEXT ===== */
var PRESETS = {
    gemini:    {name:'Gemini Flash',   base_url:'https://generativelanguage.googleapis.com/v1beta/openai', model:'gemini-2.0-flash', format:'openai'},
    groq:      {name:'Groq Llama 3.3', base_url:'https://api.groq.com/openai/v1', model:'llama-3.3-70b-versatile', format:'openai'},
    openrouter:{name:'OpenRouter',     base_url:'https://openrouter.ai/api/v1', model:'deepseek/deepseek-chat-v3.1:free', format:'openai'},
    openai:    {name:'OpenAI',         base_url:'https://api.openai.com/v1', model:'gpt-4o-mini', format:'openai'},
    deepseek:  {name:'DeepSeek',       base_url:'https://api.deepseek.com', model:'deepseek-chat', format:'openai'},
    claude:    {name:'Claude Haiku',   base_url:'https://api.anthropic.com/v1', model:'claude-haiku-4-5-20251001', format:'anthropic'},
};
function applyPreset(k){ if(!k||!PRESETS[k])return; var p=PRESETS[k];
    pName.value=p.name; pUrl.value=p.base_url; pModel.value=p.model; pFormat.value=p.format; }
function openAddText(){
    formText.reset(); formText.action=STORE_URL; mText.value='';
    textModalTitle.textContent='Tambah Provider Teks'; pKey.placeholder='sk-... / AIza...';
    showModal('modalText');
}
function openEditText(b){
    formText.reset(); formText.action=UPD_BASE+'/'+b.dataset.id; mText.value='PUT';
    textModalTitle.textContent='Edit Provider Teks';
    pName.value=b.dataset.name; pModel.value=b.dataset.model; pUrl.value=b.dataset.url; pFormat.value=b.dataset.format;
    pKey.placeholder='(biarkan kosong jika tidak diganti)';
    showModal('modalText');
}

/* ===== IMAGE ===== */
var IMG_PRESETS = {
    'pollinations':       {name:'Pollinations Flux',  model:'flux',  format:'pollinations', base_url:''},
    'pollinations-turbo': {name:'Pollinations Turbo', model:'turbo', format:'pollinations', base_url:''},
    'gemini-image':       {name:'Nano Banana',        model:'gemini-2.5-flash-image', format:'imagen', base_url:'https://generativelanguage.googleapis.com/v1beta'},
    'dalle':              {name:'DALL-E 3',           model:'dall-e-3', format:'dalle', base_url:'https://api.openai.com/v1'},
};
function applyImgPreset(k){ if(!k||!IMG_PRESETS[k])return; var p=IMG_PRESETS[k];
    iName.value=p.name; iModel.value=p.model; iFormat.value=p.format; iUrl.value=p.base_url; }
function openAddImage(){
    formImage.reset(); formImage.action=STORE_URL; mImage.value='';
    imageModalTitle.textContent='Tambah Provider Gambar'; iKey.placeholder='sk-... / AIza...';
    showModal('modalImage');
}
function openEditImage(b){
    formImage.reset(); formImage.action=UPD_BASE+'/'+b.dataset.id; mImage.value='PUT';
    imageModalTitle.textContent='Edit Provider Gambar';
    iName.value=b.dataset.name; iModel.value=b.dataset.model; iUrl.value=b.dataset.url; iFormat.value=b.dataset.format;
    iKey.placeholder='(biarkan kosong jika tidak diganti)';
    showModal('modalImage');
}

/* ===== TTS ===== */
function openAddTts(){
    formTts.reset(); formTts.action=STORE_URL; mTts.value='';
    ttsModalTitle.textContent='Tambah Provider Suara'; tName.value='Gemini TTS'; tKey.placeholder='AIza...';
    showModal('modalTts');
}
function openEditTts(b){
    formTts.reset(); formTts.action=UPD_BASE+'/'+b.dataset.id; mTts.value='PUT';
    ttsModalTitle.textContent='Edit Provider Suara';
    tName.value=b.dataset.name; tModel.value=b.dataset.model;
    tKey.placeholder='(biarkan kosong jika tidak diganti)';
    showModal('modalTts');
}
</script>

@endsection
