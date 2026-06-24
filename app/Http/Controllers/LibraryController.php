<?php

namespace App\Http\Controllers;

class LibraryController extends Controller
{
    private function songs(): array
    {
        return [
            ['title' => 'Memang Begini',    'year' => 2026, 'genre' => 'Pop Rock',         'mood' => 'energetik',    'theme' => 'Penerimaan diri',             'spotify' => 'https://open.spotify.com/album/4UKrlbmAOePUkl5YAdwlDa'],
            ['title' => 'Pantang Menyerah', 'year' => 2024, 'genre' => 'Rock Anthem',      'mood' => 'energetik',    'theme' => 'Kegigihan & perjuangan',      'spotify' => null],
            ['title' => 'Cinta Sederhana',  'year' => 2023, 'genre' => 'Soft Rock',        'mood' => 'emosional',    'theme' => 'Cinta & kerentanan',          'spotify' => null],
            ['title' => 'Musik & Doa',      'year' => 2023, 'genre' => 'Soul/Rock',        'mood' => 'spiritual',    'theme' => 'Iman, musik sebagai doa',     'spotify' => null],
            ['title' => 'Bayangan Cahaya',  'year' => 2023, 'genre' => 'Electronic Pop',   'mood' => 'spiritual',    'theme' => 'Cahaya vs bayangan',          'spotify' => null],
            ['title' => 'Langit Biru',      'year' => 2022, 'genre' => 'Pop Rock',         'mood' => 'energetik',    'theme' => 'Harapan & impian',            'spotify' => null],
            ['title' => 'Tangan Tuhan',     'year' => 2022, 'genre' => 'Gospel Rock',      'mood' => 'spiritual',    'theme' => 'Intervensi ilahi',            'spotify' => null],
            ['title' => 'Detak Jantung',    'year' => 2022, 'genre' => 'Alternative Rock', 'mood' => 'introspektif', 'theme' => 'Keaslian, denyut hidup',      'spotify' => null],
            ['title' => 'Dua Hati',         'year' => 2021, 'genre' => 'Acoustic Pop',     'mood' => 'emosional',    'theme' => 'Dualitas perspektif',         'spotify' => null],
            ['title' => 'Bulan Pagi',       'year' => 2021, 'genre' => 'Dream Pop',        'mood' => 'spiritual',    'theme' => 'Pagi & awal baru',            'spotify' => null],
            ['title' => 'Kenangan Indah',   'year' => 2021, 'genre' => 'Indie Rock',       'mood' => 'introspektif', 'theme' => 'Memori & nostalgia',          'spotify' => null],
            ['title' => 'Satu Langkah',     'year' => 2019, 'genre' => 'Acoustic Rock',    'mood' => 'energetik',    'theme' => 'Kemajuan & tekad',            'spotify' => null],
            ['title' => 'Sepi Ramai',       'year' => 2019, 'genre' => 'Indie Pop',        'mood' => 'introspektif', 'theme' => 'Kontradiksi & keseimbangan',  'spotify' => null],
            ['title' => 'Pulang',           'year' => 2020, 'genre' => 'Acoustic Ballad',  'mood' => 'emosional',    'theme' => 'Pulang & rasa memiliki',      'spotify' => null],
            ['title' => 'Malam Gelap',      'year' => 2020, 'genre' => 'Indie Folk',       'mood' => 'introspektif', 'theme' => 'Kegelapan & introspeksi',     'spotify' => null],
        ];
    }

    public function index()
    {
        $songs = $this->songs();
        $moodLabels = [
            'energetik'    => 'Energetik',
            'emosional'    => 'Emosional',
            'introspektif' => 'Introspektif',
            'spiritual'    => 'Spiritual',
        ];
        $seo = [
            'title'       => 'Diskografi Margonoandi — Semua Lagu di Spotify & Apple Music',
            'description' => 'Jelajahi semua lagu Margonoandi: pop rock, indie acoustic, gospel, electronic pop. Filter berdasarkan mood — energetik, emosional, introspektif, atau spiritual.',
            'url'         => url('/library'),
            'image'       => asset('images/Margonoandi.jpeg'),
            'schema'      => [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'       => 'MusicGroup',
                        'name'        => 'Margonoandi',
                        'url'         => url('/library'),
                        'description' => 'Musisi indie Indonesia dengan lagu pop rock, acoustic, gospel, dan electronic pop.',
                        'genre'       => ['Pop Rock', 'Indie', 'Acoustic', 'Gospel Rock'],
                    ],
                    [
                        '@type'           => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Library', 'item' => url('/library')],
                        ],
                    ],
                ],
            ],
        ];
        return view('library.index', compact('songs', 'moodLabels', 'seo'));
    }
}
