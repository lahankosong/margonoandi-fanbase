<?php

namespace App\Http\Controllers;

use App\Models\BandPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BandPostController extends Controller
{
    public function index()
    {
        $posts = collect();
        try {
            $posts = BandPost::with('user')->orderByDesc('urgent')->orderByDesc('created_at')->get();
        } catch (\Throwable $e) {
            // tabel belum ada — jalankan fixdb.php
        }
        return view('fanbase.band.index', compact('posts'));
    }

    public function create()
    {
        return view('fanbase.band.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:120',
            'description'  => 'nullable|string|max:2000',
            'roles_needed' => 'nullable|string|max:255',
            'genres'       => 'nullable|string|max:255',
            'location'     => 'nullable|string|max:120',
        ]);
        $data['user_id'] = Auth::id();
        $data['urgent']  = $request->boolean('urgent');
        $data['status']  = 'open';

        $band = BandPost::create($data);

        // Auto-post ke Kita
        $roles = $band->roles_needed
            ? implode(', ', array_map('trim', explode(',', $band->roles_needed)))
            : '';
        $body = "🎯 Cari Personil: {$band->title}";
        if ($roles)            $body .= "\nDibutuhkan: {$roles}";
        if ($band->location)   $body .= "\n📍 {$band->location}";
        if ($band->description) $body .= "\n\n" . Str::limit($band->description, 250);

        Post::create([
            'user_id'     => Auth::id(),
            'body'        => $body,
            'linked_type' => 'band',
            'linked_id'   => $band->id,
        ]);

        return redirect()->route('musisi.index', ['tab' => 'band'])
            ->with('success', 'Lowongan dipasang dan otomatis dibagikan ke Kita.');
    }

    public function show($id)
    {
        $post = BandPost::with('user')->findOrFail($id);
        return view('fanbase.band.show', compact('post'));
    }

    public function toggleStatus($id)
    {
        $post = BandPost::findOrFail($id);
        if ($post->user_id !== Auth::id()) abort(403);
        $post->update(['status' => $post->status === 'open' ? 'closed' : 'open']);
        return back()->with('success', 'Status lowongan diperbarui.');
    }

    public function destroy($id)
    {
        $post = BandPost::findOrFail($id);
        if ($post->user_id !== Auth::id()) abort(403);
        // Hapus linked kita post juga
        Post::where('linked_type', 'band')->where('linked_id', $post->id)->delete();

        $post->delete();
        return redirect()->route('musisi.index', ['tab' => 'band'])
            ->with('success', 'Lowongan dihapus.');
    }
}
