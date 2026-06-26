<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('batch')->orderBy('id')->get();
        $grouped  = $articles->groupBy('category');

        $musicians = collect();
        try {
            $musicians = \App\Models\MusicianProfile::with('user')
                ->where('is_active', true)
                ->latest('updated_at')->take(5)->get()
                ->map(fn($p) => [
                    'name'   => $p->user->name ?? 'Musisi',
                    'avatar' => $p->photoUrl(),
                ])->values();
        } catch (\Throwable $e) {}

        $latestGig = null;
        try {
            $latestGig = \App\Models\GigPost::with('user')
                ->where('status', 'open')->latest()->first();
        } catch (\Throwable $e) {}

        $latestPost = null;
        try {
            $latestPost = \App\Models\Post::with('user')->latest()->first();
        } catch (\Throwable $e) {}

        return view('library.materi', compact('articles', 'grouped', 'musicians', 'latestGig', 'latestPost'));
    }

    public function show(string $slug)
    {
        $article  = Article::where('slug', $slug)->firstOrFail();
        $prev     = Article::where('id', '<', $article->id)->orderByDesc('id')->first();
        $next     = Article::where('id', '>', $article->id)->orderBy('id')->first();

        $related = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderBy('id')->take(4)->get();

        $latestGig = null;
        try {
            $latestGig = \App\Models\GigPost::with('user')
                ->where('status', 'open')->latest()->first();
        } catch (\Throwable $e) {}

        return view('library.artikel', compact('article', 'prev', 'next', 'related', 'latestGig'));
    }

    public function download(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $content  = "# {$article->title}\n";
        $content .= "Kategori: {$article->category_label} | Waktu baca: {$article->reading_time} menit\n";
        $content .= "Sumber: margonoandi.my.id/library/materi/{$article->slug}\n\n";
        $content .= "---\n\n";
        $content .= $article->content_markdown;

        return response($content, 200, [
            'Content-Type'        => 'text/markdown; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $article->slug . '.md"',
        ]);
    }
}
