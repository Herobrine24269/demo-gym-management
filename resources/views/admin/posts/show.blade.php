@extends('layouts.dashboard')
@section('title', $post->title)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">CONTENT</span></div>
        <h1 class="page-title">{{ Str::limit($post->title, 50) }}</h1>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost"><span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit</a>
        <a href="{{ route('admin.posts.index') }}" class="btn-ghost">← Back</a>
    </div>
</div>
<div style="max-width:720px;">
    <div class="card-dark">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
            @if($post->status === 'published') <span class="badge-active">Published</span>
            @else <span class="badge-paused">Draft</span> @endif
            @if($post->category)<span class="badge-secondary">{{ $post->category->name }}</span>@endif
            <span style="font-size:11px;color:var(--color-outline);">By {{ $post->author->name }}</span>
            @if($post->published_at)<span style="font-size:11px;color:var(--color-outline);">{{ $post->published_at->format('d M Y') }}</span>@endif
        </div>
        <div style="font-size:14px;color:var(--color-on-surface-variant);line-height:1.8;white-space:pre-wrap;">{{ $post->body }}</div>
    </div>
</div>
@endsection
