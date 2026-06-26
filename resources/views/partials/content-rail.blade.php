{{-- Right rail — reusable. Drop-in: @include('partials.content-rail')
     CSS (.crail-*, .page-rail-wrap, .page-rail-aside) didefinisikan di layouts/app.blade.php
     Opsi: $skipArticlesRail (bool) untuk sembunyikan blok Materi. --}}
@php
    $rurl = function (string $name, $param = null) {
        try {
            if (! \Illuminate\Support\Facades\Route::has($name)) return null;
            if ($param === null) return route($name);
            if ($param === '' || $param === false) return null;
            return route($name, $param);
        } catch (\Throwable $e) { return null; }
    };

    $railGigs = collect();
    try { $railGigs = \App\Models\GigPost::with('user')->where('status','open')->latest()->take(3)->get(); } catch (\Throwable $e) {}

    $railArticles = collect();
    if (empty($skipArticlesRail)) {
        try { $railArticles = \App\Models\Article::orderByDesc('id')->take(4)->get(['title','slug']); } catch (\Throwable $e) {}
    }

    $railTools = [
        ['🎸', 'Chord Builder',       'tools.chord-builder'],
        ['🔀', 'Transpose Kunci',     'tools.transpose-kunci'],
        ['💰', 'Kalkulator Royalti',  'tools.kalkulator-royalti'],
        ['📄', 'EPK Generator',       'tools.epk'],
        ['🥁', 'BPM Calculator',      'tools.bpm-kalkulator'],
    ];
@endphp

<div class="crail-widget" style="margin-bottom:14px">
    @guest
    <div class="crail-h">Komunitas Musisi</div>
    <p class="crail-cta-txt">Gabung gratis: diskusi, pasang gig, dan akses semua alat & materi.</p>
    @if($u = $rurl('google.login'))<a href="{{ $u }}" class="crail-cta-btn">Masuk dengan Google →</a>@endif
    @else
    <div class="crail-h">Komunitas</div>
    <div class="crail-links">
        @if($u = $rurl('aku'))<a href="{{ $u }}" class="crail-link">👤 Profil & kartu musisi</a>@endif
        @if($u = $rurl('kita'))<a href="{{ $u }}" class="crail-link">🌍 Linimasa komunitas</a>@endif
        @if($u = $rurl('gig.board'))<a href="{{ $u }}" class="crail-link">🎪 Papan Gig & Audisi</a>@endif
    </div>
    @endguest
</div>

@if($railGigs->isNotEmpty())
<div class="crail-widget" style="margin-bottom:14px">
    <div class="crail-h">🎪 Gig Terbaru</div>
    <div class="crail-links">
        @foreach($railGigs as $g)
        @if($u = $rurl('gig.board'))
        <a href="{{ $u }}" class="crail-link crail-link-2">
            <span class="crail-link-t">{{ \Illuminate\Support\Str::limit($g->title, 40) }}</span>
            <span class="crail-link-sub">{{ $g->location ?? '' }}</span>
        </a>
        @endif
        @endforeach
    </div>
    @if($u = $rurl('gig.board'))<a href="{{ $u }}" class="crail-more">Lihat semua gig →</a>@endif
</div>
@endif

<div class="crail-widget" style="margin-bottom:14px">
    <div class="crail-h">🎛️ Alat Musisi</div>
    <div class="crail-links">
        @foreach($railTools as [$ic, $name, $route])
        @if($u = $rurl($route))<a href="{{ $u }}" class="crail-link">{{ $ic }} {{ $name }}</a>@endif
        @endforeach
    </div>
    @if($u = $rurl('tools.index'))<a href="{{ $u }}" class="crail-more">Semua alat →</a>@endif
</div>

@if($railArticles->isNotEmpty())
<div class="crail-widget">
    <div class="crail-h">📚 Baca Juga</div>
    <div class="crail-links">
        @foreach($railArticles as $a)
        @if($u = $rurl('library.materi.show', $a->slug))<a href="{{ $u }}" class="crail-link">{{ \Illuminate\Support\Str::limit($a->title, 44) }}</a>@endif
        @endforeach
    </div>
    @if($u = $rurl('library.materi'))<a href="{{ $u }}" class="crail-more">Semua materi →</a>@endif
</div>
@endif
