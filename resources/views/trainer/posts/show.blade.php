@extends('layouts.dashboard')
@section('title', 'Preview: ' . $post->title)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">{{ Str::limit($post->title, 60) }}</h1>
        <p class="page-title-sub">Post overview & publication status</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        @if($post->status === 'published')
        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span> View Live on Website
        </a>
        @endif

        @if($post->author_id === auth()->id() || auth()->user()->isAdmin())
        <form method="POST" action="{{ route('trainer.posts.publish', $post) }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-ghost">
                <span class="material-symbols-outlined" style="font-size:18px;color:{{ $post->status === 'published' ? 'var(--color-outline)' : 'var(--color-primary-container)' }};">
                    {{ $post->status === 'published' ? 'unpublished' : 'publish' }}
                </span>
                {{ $post->status === 'published' ? 'Move to Draft' : 'Publish Immediately' }}
            </button>
        </form>

        <a href="{{ route('trainer.posts.edit', $post) }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit Post
        </a>
        @endif

        <a href="{{ route('trainer.posts.index') }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back
        </a>
    </div>
</div>

<div style="max-width:880px;">
    {{-- Main Post Preview Card --}}
    <div class="card-dark" style="margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
            @if($post->status === 'published')
                <span class="badge-active">Published</span>
            @else
                <span class="badge-paused">Draft</span>
            @endif

            <span class="badge-secondary">{{ $post->category->name ?? 'General' }}</span>

            <span style="font-size:12px;color:var(--color-outline);display:flex;align-items:center;gap:4px;">
                <span class="material-symbols-outlined" style="font-size:15px;">person</span>
                By {{ $post->author->name }} {{ $post->author_id === auth()->id() ? '(You)' : '' }}
            </span>

            <span style="font-size:12px;color:var(--color-outline);display:flex;align-items:center;gap:4px;">
                <span class="material-symbols-outlined" style="font-size:15px;">schedule</span>
                {{ $post->read_time }} min read
            </span>

            @if($post->published_at)
            <span style="font-size:12px;color:var(--color-outline);display:flex;align-items:center;gap:4px;">
                <span class="material-symbols-outlined" style="font-size:15px;">calendar_today</span>
                Published {{ $post->published_at->format('d M Y, h:i A') }}
            </span>
            @endif
        </div>

        <h2 style="font-family:var(--font-heading);font-size:26px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 14px;line-height:1.2;">
            {{ $post->title }}
        </h2>

        @if($post->excerpt)
        <div style="font-size:15px;color:var(--color-on-surface-variant);line-height:1.6;padding-left:14px;border-left:3px solid var(--color-primary-container);margin-bottom:20px;">
            {{ $post->excerpt }}
        </div>
        @endif

        @if($post->image_url)
        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--color-surface-highest);max-height:380px;margin-bottom:24px;">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        </div>
        @endif

        <div style="padding-top:16px;border-top:1px solid var(--color-surface-highest);font-size:15px;color:var(--color-on-surface);line-height:1.8;">
            {!! $post->body !!}
        </div>
    </div>
</div>
@endsection
