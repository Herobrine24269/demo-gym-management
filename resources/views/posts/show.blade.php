@extends('layouts.public')
@section('title', $post->title . ' — Kinetic Apex')
@section('meta_description', Str::limit($post->excerpt, 155))

@section('content')
<article style="background:var(--color-surface-lowest);padding:40px 0 80px;">
    <div style="max-width:960px;margin:0 auto;padding:0 24px;">

        {{-- ── BREADCRUMBS ─────────────────────────────────────── --}}
        <nav style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-outline);margin-bottom:28px;flex-wrap:wrap;">
            <a href="{{ route('home') }}" style="color:var(--color-outline);text-decoration:none;" onmouseover="this.style.color='var(--color-on-surface)'" onmouseout="this.style.color='var(--color-outline)'">Home</a>
            <span>/</span>
            <a href="{{ route('posts.index') }}" style="color:var(--color-outline);text-decoration:none;" onmouseover="this.style.color='var(--color-on-surface)'" onmouseout="this.style.color='var(--color-outline)'">Posts & Guides</a>
            <span>/</span>
            <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" style="color:var(--color-outline);text-decoration:none;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-outline)'">{{ $post->category->name }}</a>
            <span>/</span>
            <span style="color:var(--color-on-surface-variant);font-weight:500;">{{ Str::limit($post->title, 35) }}</span>
        </nav>

        {{-- ── POST HEADER ─────────────────────────────────────── --}}
        <header style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
                <span class="badge-green" style="font-size:11px;padding:4px 12px;">{{ $post->category->name }}</span>
                <span style="color:var(--color-surface-highest);">|</span>
                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--color-outline);">
                    <span class="material-symbols-outlined" style="font-size:16px;">calendar_today</span>
                    {{ $post->published_at?->format('F d, Y') ?? 'Draft' }}
                </span>
                <span style="color:var(--color-surface-highest);">•</span>
                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--color-outline);">
                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                    {{ $post->read_time }} min read
                </span>
                @if($post->status === 'draft')
                    <span class="badge-paused" style="font-size:11px;">Draft Preview</span>
                @endif
            </div>

            <h1 style="font-family:var(--font-heading);font-size:clamp(30px,5vw,52px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);line-height:1.1;margin:0 0 20px;">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
            <p style="font-size:17px;color:var(--color-on-surface-variant);line-height:1.6;margin:0 0 24px;border-left:3px solid var(--color-primary-container);padding-left:16px;">
                {{ $post->excerpt }}
            </p>
            @endif

            {{-- Author byline & Action buttons --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-top:1px solid var(--color-surface-highest);border-bottom:1px solid var(--color-surface-highest);flex-wrap:wrap;gap:16px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;border-radius:50%;background:rgba(57,255,20,.15);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:15px;font-weight:700;">
                        {{ $post->author->initials() }}
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:var(--color-on-surface);">{{ $post->author->name }}</div>
                        <div style="font-size:11px;font-family:var(--font-heading);letter-spacing:.06em;color:var(--color-outline);text-transform:uppercase;">
                            {{ $post->author->isTrainer() ? ($post->author->trainer?->specialization ?? 'Certified Strength Coach') : 'Apex Athletic Director' }}
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:10px;">
                    {{-- If author or admin, show direct edit button --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost" style="padding:8px 16px;font-size:12px;">
                                <span class="material-symbols-outlined" style="font-size:16px;">edit</span> Edit (Admin)
                            </a>
                        @elseif(auth()->user()->isTrainer() && auth()->id() === $post->author_id)
                            <a href="{{ route('trainer.posts.edit', $post) }}" class="btn-ghost" style="padding:8px 16px;font-size:12px;">
                                <span class="material-symbols-outlined" style="font-size:16px;">edit</span> Edit My Post
                            </a>
                        @endif
                    @endauth

                    <button onclick="copyPostUrl()" class="btn-ghost" style="padding:8px 14px;font-size:12px;" id="copy-btn">
                        <span class="material-symbols-outlined" style="font-size:16px;">share</span> Share
                    </button>
                </div>
            </div>
        </header>

        {{-- ── FEATURED IMAGE BANNER ──────────────────────────── --}}
        @if($post->image_url)
        <div style="margin-bottom:36px;border-radius:12px;overflow:hidden;border:1px solid var(--color-surface-highest);box-shadow:0 12px 36px rgba(0,0,0,.5);max-height:500px;">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        </div>
        @endif

        {{-- ── ARTICLE BODY ────────────────────────────────────── --}}
        <div class="post-body-content" style="font-size:16px;color:var(--color-on-surface);line-height:1.8;margin-bottom:48px;">
            {!! $post->body !!}
        </div>

        {{-- ── AUTHOR SPOTLIGHT BOX ────────────────────────────── --}}
        <div style="background:var(--color-surface-low);border-radius:12px;border:1px solid var(--color-surface-highest);padding:24px 28px;margin-bottom:48px;display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
            <div style="width:60px;height:60px;border-radius:50%;background:var(--color-surface-container);border:2px solid var(--color-primary-container);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:22px;font-weight:700;flex-shrink:0;">
                {{ $post->author->initials() }}
            </div>
            <div style="flex:1;min-width:240px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">{{ $post->author->name }}</div>
                    <span class="badge-green" style="font-size:10px;">{{ $post->author->isTrainer() ? 'Certified Coach' : 'Admin' }}</span>
                </div>
                <div style="font-size:12px;color:var(--color-primary-container);margin-bottom:8px;">
                    {{ $post->author->isTrainer() ? ($post->author->trainer?->specialization ?? 'Athletic Conditioning & Hypertrophy') : 'Facility Director & Technical Staff' }}
                </div>
                <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.55;margin:0 0 12px;">
                    {{ $post->author->trainer?->bio ?? 'Kinetic Apex athletic performance staff dedicated to delivering precision strength protocols, physiological adaptation guides, and structured nutrition.' }}
                </p>
                <a href="{{ route('posts.index', ['author' => $post->author_id]) }}" style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-primary-container);text-decoration:none;text-transform:uppercase;display:inline-flex;align-items:center;gap:4px;">
                    View More Posts by {{ $post->author->name }} →
                </a>
            </div>
        </div>

        {{-- ── FOOTER ACTIONS & SHARE ──────────────────────────── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 0;border-top:1px solid var(--color-surface-highest);border-bottom:1px solid var(--color-surface-highest);margin-bottom:48px;flex-wrap:wrap;gap:14px;">
            <a href="{{ route('posts.index') }}" class="btn-ghost" style="padding:10px 20px;">
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to All Guides
            </a>

            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:12px;color:var(--color-outline);font-family:var(--font-heading);text-transform:uppercase;letter-spacing:.06em;">Share Protocol:</span>
                <button onclick="copyPostUrl()" class="btn-ghost" style="padding:8px 12px;" title="Copy Link">
                    <span class="material-symbols-outlined" style="font-size:16px;">link</span>
                </button>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="btn-ghost" style="padding:8px 12px;" title="Share on X / Twitter">
                    <span style="font-family:var(--font-heading);font-weight:700;font-size:13px;">𝕏</span>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="btn-ghost" style="padding:8px 12px;" title="Share via WhatsApp">
                    <span class="material-symbols-outlined" style="font-size:16px;">chat</span>
                </a>
            </div>
        </div>

        {{-- ── RELATED POSTS ──────────────────────────────────── --}}
        @if($relatedPosts->isNotEmpty())
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">///</span>
                <h3 style="font-family:var(--font-heading);font-size:20px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                    Related Transmissions
                </h3>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;">
                @foreach($relatedPosts as $rel)
                <article style="background:var(--color-surface-low);border-radius:10px;border:1px solid var(--color-surface-highest);overflow:hidden;display:flex;flex-direction:column;transition:transform .2s,border-color .2s;"
                         onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.transform='translateY(-2px)'"
                         onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.transform='none'">
                    <div style="height:140px;background:var(--color-surface-container);position:relative;overflow:hidden;">
                        @if($rel->image_url)
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--color-surface-high);">
                                <span class="material-symbols-outlined" style="font-size:40px;color:var(--color-surface-highest);">article</span>
                            </div>
                        @endif
                        <span class="badge-green" style="position:absolute;top:8px;left:8px;font-size:9px;">{{ $rel->category->name }}</span>
                    </div>
                    <div style="padding:16px;display:flex;flex-direction:column;flex:1;">
                        <div style="font-size:11px;color:var(--color-outline);margin-bottom:6px;">{{ $rel->published_at?->format('d M Y') }} • {{ $rel->read_time }} min</div>
                        <h4 style="font-family:var(--font-heading);font-size:15px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 8px;line-height:1.25;">
                            <a href="{{ route('posts.show', $rel->slug) }}" style="color:var(--color-on-surface);text-decoration:none;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-on-surface)'">
                                {{ $rel->title }}
                            </a>
                        </h4>
                        <div style="margin-top:auto;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:11px;color:var(--color-outline);">By {{ $rel->author->name }}</span>
                            <a href="{{ route('posts.show', $rel->slug) }}" style="font-family:var(--font-heading);font-size:10px;letter-spacing:.06em;color:var(--color-primary-container);text-decoration:none;text-transform:uppercase;">Read →</a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>

@push('scripts')
<style>
.post-body-content h2 {
    font-family: var(--font-heading);
    font-size: 24px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-on-surface);
    margin: 32px 0 14px;
    letter-spacing: .02em;
}
.post-body-content h3 {
    font-family: var(--font-heading);
    font-size: 19px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--color-primary-container);
    margin: 24px 0 10px;
    letter-spacing: .03em;
}
.post-body-content p {
    margin: 0 0 18px;
    color: var(--color-on-surface-variant);
}
.post-body-content blockquote {
    background: var(--color-surface-low);
    border-left: 4px solid var(--color-primary-container);
    margin: 24px 0;
    padding: 16px 20px;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: var(--color-on-surface);
}
.post-body-content ul, .post-body-content ol {
    margin: 0 0 20px 20px;
    padding: 0;
    color: var(--color-on-surface-variant);
}
.post-body-content li {
    margin-bottom: 8px;
    line-height: 1.6;
}
.post-body-content strong {
    color: var(--color-on-surface);
    font-weight: 600;
}
</style>
<script>
function copyPostUrl() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('copy-btn');
        if (btn) {
            const original = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">check</span> Copied!';
            setTimeout(() => { btn.innerHTML = original; }, 2500);
        }
    });
}
</script>
@endpush
@endsection
