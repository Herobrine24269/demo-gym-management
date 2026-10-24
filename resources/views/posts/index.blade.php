@extends('layouts.public')
@section('title', 'Posts & Training Protocols — Kinetic Apex')
@section('meta_description', 'Explore evidence-based workout guides, nutrition frameworks, and facility updates from Kinetic Apex certified trainers.')

@section('content')
{{-- ── HERO SECTION ───────────────────────────────────────────── --}}
<section style="position:relative;background:var(--color-surface-lowest);padding:64px 0 48px;border-bottom:1px solid var(--color-surface-highest);overflow:hidden;">
    <div style="position:absolute;top:-120px;left:50%;transform:translateX(-50%);width:700px;height:350px;background:rgba(57,255,20,.07);filter:blur(110px);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;top:20%;right:-80px;width:300px;height:300px;background:rgba(129,207,255,.05);filter:blur(90px);border-radius:50%;pointer-events:none;"></div>

    <div style="max-width:1280px;margin:0 auto;padding:0 32px;position:relative;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:24px;">
            <div style="max-width:760px;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--color-surface-high);color:var(--color-primary-container);margin-bottom:18px;">
                    <span class="pulse-green" style="width:8px;height:8px;"></span>
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Apex Transmission Hub /// Verified Field Guides</span>
                </div>
                <h1 style="font-family:var(--font-heading);font-size:clamp(32px,5vw,54px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);line-height:1.05;margin:0 0 16px;">
                    Gym Posts, Protocols & <span style="color:var(--color-primary-container);">Performance Guides.</span>
                </h1>
                <p style="font-size:15px;color:var(--color-on-surface-variant);line-height:1.6;margin:0;">
                    Evidence-based strength directives, nutrition blueprints, and recovery methodologies published directly by certified Kinetic Apex coaches and athletic directors for our entire community.
                </p>
            </div>

            {{-- Quick action if Admin or Trainer is logged in --}}
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isTrainer())
                <div style="display:flex;align-items:center;gap:12px;">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.posts.create') : route('trainer.posts.create') }}" class="btn-primary" style="padding:12px 24px;font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">edit_square</span>
                        + Write New Post
                    </a>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.posts.index') : route('trainer.posts.index') }}" class="btn-ghost" style="padding:12px 18px;font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">settings</span>
                        Manage Posts
                    </a>
                </div>
                @endif
            @endauth
        </div>

        {{-- ── FILTERS & SEARCH BAR ───────────────────────────── --}}
        <div style="margin-top:40px;background:var(--color-surface-low);border-radius:12px;padding:16px 20px;border:1px solid var(--color-surface-highest);display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
            {{-- Category Filter Pills --}}
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;">
                <a href="{{ route('posts.index', array_filter(['q' => $search])) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;text-transform:uppercase;text-decoration:none;transition:all .15s;{{ !$activeCategory ? 'background:var(--color-primary-container);color:var(--color-on-primary);font-weight:700;' : 'background:var(--color-surface-container);color:var(--color-on-surface-variant);border:1px solid var(--color-surface-highest);' }}">
                    All Topics
                    <span style="font-size:10px;opacity:.8;">({{ $totalPublishedCount }})</span>
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('posts.index', array_filter(['category' => $cat->slug, 'q' => $search])) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;text-transform:uppercase;text-decoration:none;transition:all .15s;{{ ($activeCategory && $activeCategory->id === $cat->id) ? 'background:var(--color-primary-container);color:var(--color-on-primary);font-weight:700;' : 'background:var(--color-surface-container);color:var(--color-on-surface-variant);border:1px solid var(--color-surface-highest);' }}">
                    {{ $cat->name }}
                    <span style="font-size:10px;opacity:.8;">({{ $cat->posts_count }})</span>
                </a>
                @endforeach
            </div>

            {{-- Search Input --}}
            <form method="GET" action="{{ route('posts.index') }}" style="display:flex;align-items:center;gap:8px;margin:0;min-width:260px;">
                @if($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                @endif
                <div style="position:relative;width:100%;">
                    <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:16px;color:var(--color-outline);pointer-events:none;">search</span>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search protocols, nutrition..."
                           style="width:100%;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);color:var(--color-on-surface);border-radius:6px;padding:8px 32px 8px 32px;font-size:12px;outline:none;"
                           onfocus="this.style.borderColor='var(--color-primary-container)'" onblur="this.style.borderColor='var(--color-surface-highest)'">
                    @if($search !== '')
                    <a href="{{ route('posts.index', array_filter(['category' => $activeCategory?->slug])) }}" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--color-outline);text-decoration:none;font-size:16px;">×</a>
                    @endif
                </div>
                <button type="submit" class="btn-primary" style="padding:8px 14px;font-size:11px;">Search</button>
            </form>
        </div>
    </div>
</section>

{{-- ── MAIN CONTENT ───────────────────────────────────────────── --}}
<div style="max-width:1280px;margin:0 auto;padding:48px 32px 80px;">

    {{-- Filter indicators if active --}}
    @if($search !== '' || $activeCategory)
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;padding:12px 16px;background:var(--color-surface-container);border-radius:8px;border:1px solid var(--color-surface-highest);">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--color-on-surface-variant);">
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">filter_alt</span>
            <span>Showing results for:</span>
            @if($activeCategory)
                <span class="badge-green">{{ $activeCategory->name }}</span>
            @endif
            @if($search !== '')
                <span style="color:var(--color-on-surface);font-weight:600;">"{{ $search }}"</span>
            @endif
        </div>
        <a href="{{ route('posts.index') }}" style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-outline);text-decoration:none;text-transform:uppercase;">Clear All Filters ✕</a>
    </div>
    @endif

    {{-- ── FEATURED HERO POST (First page with no search) ────── --}}
    @if($featuredPost)
    <div style="margin-bottom:48px;">
        <div style="display:inline-flex;align-items:center;gap:8px;color:var(--color-primary-container);font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">
            <span>///</span> FEATURED TRANSMISSION
        </div>
        <article style="background:var(--color-surface-low);border-radius:14px;border:1px solid var(--color-surface-highest);overflow:hidden;display:grid;grid-template-columns:5fr 4fr;gap:0;box-shadow:0 8px 32px rgba(0,0,0,.4);transition:box-shadow .3s,border-color .3s;"
                 onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.boxShadow='var(--glow-green)'"
                 onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.boxShadow='0 8px 32px rgba(0,0,0,.4)'">
            {{-- Image / Visual --}}
            <div style="position:relative;background:var(--color-surface-container);min-height:340px;overflow:hidden;">
                @if($featuredPost->image_url)
                    <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .5s ease;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:radial-gradient(circle at center, var(--color-surface-high) 0%, var(--color-surface-lowest) 100%);">
                        <span class="material-symbols-outlined" style="font-size:96px;color:var(--color-surface-highest);">fitness_center</span>
                    </div>
                @endif
                <div style="position:absolute;inset:0;background:linear-gradient(to right, transparent 60%, rgba(28,27,27,.9) 100%);"></div>
                <div style="position:absolute;top:16px;left:16px;">
                    <span class="badge-green" style="font-size:11px;padding:4px 12px;">{{ $featuredPost->category->name }}</span>
                </div>
            </div>

            {{-- Body Content --}}
            <div style="padding:36px;display:flex;flex-direction:column;justify-content:center;">
                <div style="display:flex;align-items:center;gap:12px;color:var(--color-outline);font-size:12px;margin-bottom:12px;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <span class="material-symbols-outlined" style="font-size:15px;">calendar_today</span>
                        {{ $featuredPost->published_at?->format('d M Y') }}
                    </span>
                    <span>•</span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <span class="material-symbols-outlined" style="font-size:15px;">schedule</span>
                        {{ $featuredPost->read_time }} min read
                    </span>
                </div>

                <h2 style="font-family:var(--font-heading);font-size:clamp(22px,2.5vw,30px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 14px;line-height:1.2;">
                    <a href="{{ route('posts.show', $featuredPost->slug) }}" style="color:var(--color-on-surface);text-decoration:none;transition:color .2s;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-on-surface)'">
                        {{ $featuredPost->title }}
                    </a>
                </h2>

                <p style="font-size:14px;color:var(--color-on-surface-variant);line-height:1.6;margin:0 0 24px;flex:1;">
                    {{ Str::limit($featuredPost->excerpt, 170) }}
                </p>

                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:16px;border-top:1px solid var(--color-surface-highest);flex-wrap:wrap;gap:12px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:rgba(57,255,20,.15);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:13px;font-weight:700;">
                            {{ $featuredPost->author->initials() }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);">{{ $featuredPost->author->name }}</div>
                            <div style="font-size:10px;font-family:var(--font-heading);letter-spacing:.06em;color:var(--color-outline);text-transform:uppercase;">
                                {{ $featuredPost->author->isTrainer() ? ($featuredPost->author->trainer?->specialization ?? 'Certified Coach') : 'Facility Director' }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('posts.show', $featuredPost->slug) }}" class="btn-primary" style="padding:10px 20px;">
                        Read Protocol <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
                    </a>
                </div>
            </div>
        </article>
    </div>
    @endif

    {{-- ── POSTS GRID ────────────────────────────────────────── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">///</span>
            <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                All Transmissions & Field Guides
            </h2>
        </div>
        <div style="font-size:12px;color:var(--color-outline);">
            Showing {{ $posts->count() }} of {{ $posts->total() }} guides
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
        @forelse($posts as $post)
        <article style="display:flex;flex-direction:column;background:var(--color-surface-low);border-radius:12px;overflow:hidden;border:1px solid var(--color-surface-highest);transition:transform .2s,box-shadow .2s,border-color .2s;"
                 onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.boxShadow='var(--glow-green)';this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.boxShadow='none';this.style.transform='none'">
            
            {{-- Card Image Header --}}
            <div style="height:190px;background:var(--color-surface-container);position:relative;overflow:hidden;">
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .4s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:radial-gradient(circle at center, var(--color-surface-high) 0%, var(--color-surface-lowest) 100%);">
                        <span class="material-symbols-outlined" style="font-size:54px;color:var(--color-surface-highest);">article</span>
                    </div>
                @endif
                <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(28,27,27,.9) 0%, transparent 60%);"></div>
                <div style="position:absolute;top:12px;left:12px;">
                    <span class="badge-green" style="font-size:10px;">{{ $post->category->name }}</span>
                </div>
                <div style="position:absolute;bottom:10px;right:12px;font-size:11px;color:var(--color-on-surface-variant);display:flex;align-items:center;gap:4px;background:rgba(19,19,19,.85);padding:2px 8px;border-radius:4px;">
                    <span class="material-symbols-outlined" style="font-size:13px;color:var(--color-primary-container);">schedule</span>
                    {{ $post->read_time }} min read
                </div>
            </div>

            {{-- Card Content --}}
            <div style="padding:20px;display:flex;flex-direction:column;flex:1;">
                <div style="display:flex;align-items:center;gap:6px;color:var(--color-outline);font-size:11px;margin-bottom:8px;">
                    <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                    {{ $post->published_at?->format('d M Y') }}
                </div>

                <h3 style="font-family:var(--font-heading);font-size:17px;font-weight:600;text-transform:uppercase;line-height:1.3;margin:0 0 10px;">
                    <a href="{{ route('posts.show', $post->slug) }}" style="color:var(--color-on-surface);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-on-surface)'">
                        {{ $post->title }}
                    </a>
                </h3>

                <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.55;margin:0 0 16px;flex:1;">
                    {{ Str::limit($post->excerpt, 110) }}
                </p>

                {{-- Author & Link footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid var(--color-surface-highest);">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:28px;height:28px;border-radius:50%;background:rgba(57,255,20,.15);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;">
                            {{ $post->author->initials() }}
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:var(--color-on-surface);line-height:1.2;">{{ $post->author->name }}</div>
                            <div style="font-size:9px;font-family:var(--font-heading);letter-spacing:.05em;color:var(--color-outline);text-transform:uppercase;">
                                {{ $post->author->isTrainer() ? 'Coach' : 'Admin' }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('posts.show', $post->slug) }}" style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-primary-container);text-decoration:none;text-transform:uppercase;display:inline-flex;align-items:center;gap:4px;">
                        Read <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                    </a>
                </div>
            </div>
        </article>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:64px 20px;background:var(--color-surface-low);border-radius:12px;border:1px dashed var(--color-surface-highest);">
            <span class="material-symbols-outlined" style="font-size:48px;color:var(--color-outline);margin-bottom:12px;">search_off</span>
            <div style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:6px;">No Guides Found</div>
            <p style="font-size:13px;color:var(--color-on-surface-variant);max-width:380px;margin:0 auto 16px;">We could not find any training protocols matching your current search or category filter.</p>
            <a href="{{ route('posts.index') }}" class="btn-primary" style="padding:8px 18px;font-size:11px;">Reset Search & View All</a>
        </div>
        @endforelse
    </div>

    {{-- ── PAGINATION ────────────────────────────────────────── --}}
    @if($posts->hasPages())
    <div style="margin-top:40px;display:flex;justify-content:center;">
        <div class="pagination-dark">
            {{ $posts->links() }}
        </div>
    </div>
    @endif
</div>

@push('scripts')
<style>
@media(max-width:900px){
    article[style*="grid-template-columns:5fr 4fr"] {
        grid-template-columns: 1fr!important;
    }
}
.pagination-dark nav svg {
    width: 18px;
    height: 18px;
}
.pagination-dark nav {
    display: inline-flex;
    background: var(--color-surface-low);
    border: 1px solid var(--color-surface-highest);
    border-radius: 8px;
    padding: 6px 12px;
}
.pagination-dark nav a, .pagination-dark nav span {
    color: var(--color-on-surface);
    font-size: 13px;
    padding: 6px 10px;
    text-decoration: none;
}
.pagination-dark nav span[aria-current="page"] span {
    background: var(--color-primary-container);
    color: var(--color-on-primary);
    border-radius: 4px;
    font-weight: 700;
}
</style>
@endpush
@endsection
