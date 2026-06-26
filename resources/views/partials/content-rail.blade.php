{{-- Right rail discovery/konversi — flat & reusable. Drop-in: @include('partials.content-rail').
     Anti-gagal: setiap route() dijaga ($rurl mengembalikan null jika route tak ada / parameter tak valid),
     sehingga satu link bermasalah tidak pernah men-500-kan seluruh halaman.
     Opsi: $skipArticlesRail (bool) untuk sembunyikan blok Materi. --}}
@php
    // Helper: URL aman. Return null jika route tidak terdaftar atau gagal di-generate.
    $rurl = function (string $name, $param = null) {
        try {
            if (! \Illuminate\Support\Facades\Route::has($name)) return null;
            if ($param === null) return route($name);
            if ($param === '' || $param === false) return null;
            return route($name, $param);
        } catch (\Throwable $e) {
            return null;
        }
    };

    $railGigs = collect();
    try { $railGigs = \App\Models\GigPost::with('user')->where('status', 'open')->latest()->take(3)->get(); } catch (\Throwable $e) {}

    $railSongs = collect();
    try { $railSongs = \App\Models\Song::where('is_active', true)->orderByDesc('id')->take(3)->get(['title', 'slug', 'spotify_url']); } catch (\Throwable $e) {}

    $railArticles = collect();
    if (empty($skipArticlesRail)) {
        try { $railArticles = \App\Models\Article::orderByDesc('id')->take(4)->get(['title', 'slug']); } catch (\Throwable $e) {}
    }

    $railTools = [
        ['🎤', 'Hapus Vokal (Karaoke)', 'tools.hapus-vokal'],
        ['✂️', 'Pemotong Lagu',          'tools.potong-lagu'],
        ['💰', 'Kalkulator Royalti',     'tools.kalkulator-royalti'],
        ['📄', 'EPK Generator',          'tools.epk'],
    ];
    $gigBoardUrl = $rurl('gig.board');
@endphp

<aside class="crail">

    {{-- Komunitas / konversi --}}
    <div class="crail-block crail-cta">
        @guest
        <div class="crail-h">Komunitas Musisi</div>
        <p class="crail-cta-txt">Gabung gratis: diskusi, pasang gig, dan akses semua alat & materi.</p>
        @if($u = $rurl('google.login'))<a href="{{ $u }}" class="crail-cta-btn">Masuk dengan Google →</a>@endif
        @else
        <div class="crail-h">Komunitas</div>
        <div class="crail-links">
            @if($u = $rurl('aku'))<a href="{{ $u }}" class="crail-link">👤 Aku — profil & kartu musisi</a>@endif
            @if($u = $rurl('kita'))<a href="{{ $u }}" class="crail-link">🌍 Kita — linimasa komunitas</a>@endif
            @if($u = $rurl('dia'))<a href="{{ $u }}" class="crail-link">💬 Dia — pesan & grup</a>@endif
        </div>
        @endguest
    </div>

    {{-- Gig terbaru --}}
    @if($railGigs->isNotEmpty() && $gigBoardUrl)
    <div class="crail-block">
        <div class="crail-h">🎪 Gig Terbaru</div>
        <div class="crail-links">
            @foreach($railGigs as $g)
            <a href="{{ $gigBoardUrl }}" class="crail-link crail-link-2">
                <span class="crail-link-t">{{ \Illuminate\Support\Str::limit($g->title, 42) }}</span>
                <span class="crail-link-sub">{{ \App\Models\GigPost::typeLabel($g->type) }}{{ $g->location ? ' · ' . $g->location : '' }}</span>
            </a>
            @endforeach
        </div>
        <a href="{{ $gigBoardUrl }}" class="crail-more">Lihat semua gig →</a>
    </div>
    @endif

    {{-- Alat populer --}}
    <div class="crail-block">
        <div class="crail-h">🎛️ Alat Populer</div>
        <div class="crail-links">
            @foreach($railTools as [$ic, $name, $route])
            @if($u = $rurl($route))<a href="{{ $u }}" class="crail-link">{{ $ic }} {{ $name }}</a>@endif
            @endforeach
        </div>
        @if($u = $rurl('tools.index'))<a href="{{ $u }}" class="crail-more">Semua alat →</a>@endif
    </div>

    {{-- Materi (opsional) --}}
    @if($railArticles->isNotEmpty())
    <div class="crail-block">
        <div class="crail-h">📚 Baca Juga</div>
        <div class="crail-links">
            @foreach($railArticles as $a)
            @if($u = $rurl('library.materi.show', $a->slug))<a href="{{ $u }}" class="crail-link">{{ \Illuminate\Support\Str::limit($a->title, 48) }}</a>@endif
            @endforeach
        </div>
        @if($u = $rurl('library.materi'))<a href="{{ $u }}" class="crail-more">Semua materi →</a>@endif
    </div>
    @endif

    {{-- Rilis terbaru --}}
    @if($railSongs->isNotEmpty())
    <div class="crail-block">
        <div class="crail-h">🎵 Rilis Terbaru</div>
        <div class="crail-links">
            @foreach($railSongs as $s)
            @if($u = $rurl('song.show', $s->slug))<a href="{{ $u }}" class="crail-link">{{ \Illuminate\Support\Str::limit($s->title, 44) }}</a>@endif
            @endforeach
        </div>
        @if($u = $rurl('library'))<a href="{{ $u }}" class="crail-more">Diskografi →</a>@endif
    </div>
    @endif

</aside>
