@extends('layouts.app')

@push('styles')
<style>
    .thread-layout {
        display: grid; grid-template-columns: 1fr 240px;
        gap: 1.5rem; align-items: start;
    }
    .thread-main { min-width: 0; }
    .thread-sidebar { position: sticky; top: 80px; }

    .thread-header-card {
        background: var(--bg); border: 1px solid var(--border-2);
        border-radius: 12px; padding: 1.25rem; margin-bottom: 1.25rem;
    }
    .thread-breadcrumb {
        font-size: 11px; color: var(--text-4); margin-bottom: 1rem;
        display: flex; align-items: center; gap: 6px;
        overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
    }
    .thread-breadcrumb a { color: var(--text-4); text-decoration: none; transition: 0.15s; }
    .thread-breadcrumb a:hover { color: var(--text-2); }
    .thread-category-badge {
        display: inline-block; font-size: 10px; letter-spacing: 0.15em;
        color: var(--accent); background: var(--accent-glow); border: 1px solid var(--accent-dim);
        padding: 3px 10px; border-radius: 20px; margin-bottom: 0.75rem;
        text-transform: capitalize;
    }
    .thread-title {
        font-size: 1.3rem; font-weight: 300; margin-bottom: 1rem;
        line-height: 1.4;
    }
    .thread-body-text {
        font-size: 14px; color: var(--text-2); line-height: 1.9;
        white-space: pre-wrap; word-break: break-word;
    }
    .thread-meta-row {
        display: flex; align-items: center; gap: 8px;
        padding-top: 1rem; margin-top: 1rem;
        border-top: 1px solid var(--border-2); flex-wrap: wrap;
    }
    .thread-author-avatar {
        width: 26px; height: 26px; border-radius: 50%;
        object-fit: cover; background: var(--bg-2);
    }
    .thread-author-name { font-size: 12px; color: var(--text-3); }
    .thread-date { font-size: 11px; color: var(--text-4); }
    .thread-views { font-size: 11px; color: var(--text-4); margin-left: auto; }

    .locked-notice {
        background: var(--bg-2); border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 14px; margin-bottom: 1.25rem;
        font-size: 12px; color: var(--text-3); display: flex; align-items: center; gap: 8px;
    }
    .replies-header {
        font-size: 11px; color: var(--text-4); letter-spacing: 0.2em;
        text-transform: uppercase; margin-bottom: 1rem;
        padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-2);
    }
    .reply-card {
        background: var(--bg); border: 1px solid var(--border-2);
        border-radius: 10px; padding: 1rem; margin-bottom: 0.75rem;
    }
    .reply-header {
        display: flex; align-items: center; gap: 8px; margin-bottom: 8px;
    }
    .reply-avatar {
        width: 30px; height: 30px; border-radius: 50%;
        object-fit: cover; background: var(--bg-2); flex-shrink: 0;
    }
    .reply-name { font-size: 12px; font-weight: 500; color: var(--text); }
    .reply-time { font-size: 11px; color: var(--text-4); margin-left: 4px; }
    .reply-body {
        font-size: 14px; color: var(--text-2); line-height: 1.8;
        white-space: pre-wrap; word-break: break-word;
    }
    .reply-form-card {
        background: var(--bg); border: 1px solid var(--border-2);
        border-radius: 12px; padding: 1.25rem; margin-top: 1.25rem;
    }
    .reply-form-title {
        font-size: 11px; color: var(--text-4); letter-spacing: 0.15em;
        text-transform: uppercase; margin-bottom: 0.875rem;
    }
    .reply-textarea {
        width: 100%; background: var(--bg-2); border: 1px solid var(--border);
        border-radius: 8px; color: var(--text); font-size: 14px;
        padding: 10px 12px; outline: none; resize: vertical;
        min-height: 100px; line-height: 1.7; font-family: inherit; transition: 0.15s;
    }
    .reply-textarea:focus { border-color: var(--accent); }
    .reply-textarea::placeholder { color: var(--text-4); }
    .reply-submit {
        margin-top: 10px; padding: 9px 24px; border-radius: 8px;
        font-size: 13px; font-weight: 500; background: var(--text); color: var(--bg);
        border: none; cursor: pointer; transition: 0.2s;
    }
    .reply-submit:hover { opacity: 0.85; }
    .login-to-reply {
        background: var(--bg); border: 1px solid var(--border-2);
        border-radius: 10px; padding: 1.25rem; text-align: center; margin-top: 1.25rem;
    }
    .login-to-reply p { font-size: 13px; color: var(--text-3); margin-bottom: 1rem; }
    .btn-login-reply {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 20px; border-radius: 50px;
        background: var(--text); color: var(--bg); font-size: 13px;
        font-weight: 500; text-decoration: none;
    }
    .sidebar-info {
        background: var(--bg); border: 1px solid var(--border-2);
        border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem;
    }
    .sidebar-title {
        font-size: 11px; color: var(--text-4); letter-spacing: 0.15em;
        text-transform: uppercase; margin-bottom: 0.875rem;
        padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-2);
    }
    .sidebar-stat { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .sidebar-stat-label { font-size: 12px; color: var(--text-4); }
    .sidebar-stat-value { font-size: 12px; color: var(--text-2); font-weight: 500; }

    .alert-success {
        background: rgba(74,222,128,0.08); color: #4ade80; border: 1px solid #166534;
        padding: 10px 16px; border-radius: 8px; margin-bottom: 1rem; font-size: 13px;
    }
    .alert-error {
        background: rgba(239,68,68,0.07); color: #f87171; border: 1px solid rgba(239,68,68,0.18);
        padding: 10px 16px; border-radius: 8px; margin-bottom: 1rem; font-size: 13px;
    }
    .empty-replies {
        text-align: center; padding: 2rem; color: var(--text-4); font-size: 13px;
    }

    .thread-mobile-meta {
        display: none;
        position: fixed; bottom: 60px; left: 0; right: 0;
        background: var(--bottom-bg); border-top: 1px solid var(--border-2);
        padding: 8px 1rem; z-index: 100;
        flex-direction: row; align-items: center; gap: 10px;
        font-size: 11px; color: var(--text-4);
    }

    @media (max-width: 768px) {
        .thread-layout { grid-template-columns: 1fr; }
        .thread-sidebar { display: none; }
        .thread-mobile-meta { display: flex; }
        .thread-title { font-size: 1.1rem; }
        main { padding-bottom: 8rem !important; }
        .reply-form-card { margin-bottom: 1rem; }
    }
</style>
@endpush

@section('content')

<div class="thread-layout">

    <div class="thread-main">

        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="thread-header-card">
            <div class="thread-breadcrumb">
                <a href="{{ route('community.index') }}">Komunitas</a>
                <span>›</span>
                <a href="{{ route('community.threads') }}">Diskusi</a>
                <span>›</span>
                <span style="color:var(--text-3);">{{ Str::limit($thread->title, 30) }}</span>
            </div>

            <span class="thread-category-badge">{{ $thread->category }}</span>

            <h1 class="thread-title">
                @if($thread->is_pinned) &#128204; @endif
                {{ $thread->title }}
            </h1>

            <div class="thread-body-text">{{ $thread->body }}</div>

            <div class="thread-meta-row">
                <img src="{{ $thread->user->avatar ?? asset('images/default-avatar.png') }}"
                     class="thread-author-avatar" alt="">
                <span class="thread-author-name">{{ $thread->user->name }}</span>
                <span class="thread-date">{{ $thread->created_at->diffForHumans() }}</span>
                <span class="thread-views">&#128065; {{ $thread->views_count }}</span>
                @auth
                @if(Auth::id() === $thread->user_id && !$thread->is_locked)
                <form method="POST" action="{{ route('community.thread.destroy', $thread->id) }}"
                      onsubmit="return confirm('Hapus thread ini?')" style="margin-left:auto;">
                    @csrf @method('DELETE')
                    <button type="submit"
                        style="font-size:11px;color:var(--text-4);background:transparent;border:none;cursor:pointer;"
                        onmouseover="this.style.color='#ef4444'"
                        onmouseout="this.style.color='var(--text-4)'">Hapus</button>
                </form>
                @endif
                @endauth
            </div>
        </div>

        @if($thread->is_locked)
        <div class="locked-notice">&#128274; Thread ini sudah dikunci.</div>
        @endif

        @if($thread->replies->count() > 0)
        <p class="replies-header">{{ $thread->replies_count }} Balasan</p>
        @foreach($thread->replies as $reply)
        <div class="reply-card">
            <div class="reply-header">
                <img src="{{ $reply->user->avatar ?? asset('images/default-avatar.png') }}"
                     class="reply-avatar" alt="">
                <div>
                    <span class="reply-name">{{ $reply->user->name }}</span>
                    <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="reply-body">{{ $reply->body }}</div>
        </div>
        @endforeach
        @else
        <div class="empty-replies">Belum ada balasan. Jadilah yang pertama!</div>
        @endif

        @if(!$thread->is_locked)
            @auth
            <div class="reply-form-card">
                <p class="reply-form-title">Tulis Balasan</p>
                <form method="POST" action="{{ route('community.thread.reply', $thread->id) }}">
                    @csrf
                    <textarea name="body" class="reply-textarea"
                        placeholder="Tulis balasanmu..." required maxlength="2000"></textarea>
                    <button type="submit" class="reply-submit">Kirim Balasan</button>
                </form>
            </div>
            @else
            <div class="login-to-reply">
                <p>Login untuk membalas thread ini.</p>
                <a href="{{ route('google.login') }}" class="btn-login-reply">
                    <img src="https://www.google.com/favicon.ico" alt="G" style="width:14px;">
                    Masuk dengan Google
                </a>
            </div>
            @endauth
        @endif

    </div>

    {{-- SIDEBAR desktop --}}
    <div class="thread-sidebar">
        <div class="sidebar-info">
            <p class="sidebar-title">Info Thread</p>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Kategori</span>
                <span class="sidebar-stat-value" style="text-transform:capitalize;">{{ $thread->category }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Balasan</span>
                <span class="sidebar-stat-value">{{ $thread->replies_count }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Dilihat</span>
                <span class="sidebar-stat-value">{{ $thread->views_count }}</span>
            </div>
            <div class="sidebar-stat">
                <span class="sidebar-stat-label">Dibuat</span>
                <span class="sidebar-stat-value">{{ $thread->created_at->format('d M Y') }}</span>
            </div>
        </div>
        <div class="sidebar-info">
            <p class="sidebar-title">Navigasi</p>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('community.threads') }}"
                   style="font-size:12px;color:var(--text-2);text-decoration:none;padding:7px;border-radius:6px;background:var(--bg-2);display:block;">
                    ← Semua diskusi
                </a>
                @auth
                <a href="{{ route('community.thread.create') }}"
                   style="font-size:12px;color:var(--text-3);text-decoration:none;padding:7px;border-radius:6px;background:var(--bg-2);display:block;">
                    + Buat thread baru
                </a>
                @endauth
            </div>
        </div>
    </div>

</div>

{{-- Mobile meta bar --}}
<div class="thread-mobile-meta">
    <a href="{{ route('community.threads') }}" style="color:var(--text-3);text-decoration:none;">← Diskusi</a>
    <span>&#128172; {{ $thread->replies_count }}</span>
    <span>&#128065; {{ $thread->views_count }}</span>
    <span style="text-transform:capitalize;color:var(--accent);">{{ $thread->category }}</span>
</div>

@endsection
