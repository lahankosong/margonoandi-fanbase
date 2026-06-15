<?php

namespace App\Http\Controllers;

use App\Models\BandPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        BandPost::create($data);

        return redirect()->route('band.index')->with('success', 'Lowongan personil dipasang.');
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
        $post->delete();
        return redirect()->route('band.index')->with('success', 'Lowongan dihapus.');
    }
}
