{{-- Right rail discovery/konversi — flat & reusable. Drop-in: @include('partials.content-rail').
     Data di-fetch mandiri dengan fallback aman. Opsi: $skipArticlesRail (bool) untuk sembunyikan blok Materi. --}}
@php
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
@endphp

<aside class="crail">

    {{-- Komunitas / konversi --}}
    <div class="crail-block crail-cta">
        @guest
        <div class="crail-h">Komunitas Musisi</div>
        <p class="crail-cta-txt">Gabung gratis: diskusi, pasang gig, dan akses semua alat & materi.</p>
        <a href="{{ route('google.login') }}" class="crail-cta-btn">Masuk dengan Google →</a>
        @else
        <div class="crail-h">Komunitas</div>
        <div class="crail-links">
            <a href="{{ route('aku') }}" class="crail-link">👤 Aku — profil & kartu musisi</a>
            <a href="{{ route('kita') }}" class="crail-link">🌍 Kita — linimasa komunitas</a>
            <a href="{{ route('dia') }}" class="crail-link">💬 Dia — pesan & grup</a>
        </div>
        @endguest
    </div>

    {{-- Gig terbaru --}}
    @if($railGigs->isNotEmpty())
    <div class="crail-block">
        <div class="crail-h">🎪 Gig Terbaru</div>
        <div class="crail-links">
            @foreach($railGigs as $g)
            <a href="{{ route('gig.board') }}" class="crail-link crail-link-2">
                <span class="crail-link-t">{{ \Illuminate\Support\Str::limit($g->title, 42) }}</span>
                <span class="crail-link-sub">{{ \App\Models\GigPost::typeLabel($g->type) }}{{ $g->location ? ' · ' . $g->location : '' }}</span>
            </a>
            @endforeach
        </div>
        <a href="{{ route('gig.board') }}" class="crail-more">Lihat semua gig →</a>
    </div>
    @endif

    {{-- Alat populer --}}
    <div class="crail-block">
        <div class="crail-h">🎛️ Alat Populer</div>
        <div class="crail-links">
            @foreach($railTools as [$ic, $name, $route])
            <a href="{{ route($route) }}" class="crail-link">{{ $ic }} {{ $name }}</a>
            @endforeach
        </div>
        <a href="{{ route('tools.index') }}" class="crail-more">Semua alat →</a>
    </div>

    {{-- Materi (opsional) --}}
    @if($railArticles->isNotEmpty())
    <div class="crail-block">
        <div class="crail-h">📚 Baca Juga</div>
        <div class="crail-links">
            @foreach($railArticles as $a)
            <a href="{{ route('library.materi.show', $a->slug) }}" class="crail-link">{{ \Illuminate\Support\Str::limit($a->title, 48) }}</a>
            @endforeach
        </div>
        <a href="{{ route('library.materi') }}" class="crail-more">Semua materi →</a>
    </div>
    @endif

    {{-- Rilis terbaru --}}
    @if($railSongs->isNotEmpty())
    <div class="crail-block">
        <div class="crail-h">🎵 Rilis Terbaru</div>
        <div class="crail-links">
            @foreach($railSongs as $s)
            <a href="{{ route('song.show', $s->slug) }}" class="crail-link">{{ \Illuminate\Support\Str::limit($s->title, 44) }}</a>
            @endforeach
        </div>
        <a href="{{ route('library') }}" class="crail-more">Diskografi →</a>
    </div>
    @endif

</aside>
