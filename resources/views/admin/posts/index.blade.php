@extends('layouts.dashboard')
@section('title', 'Posts Management — Admin')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">COMMUNITY & CONTENT</span></div>
        <h1 class="page-title">Manage Posts & Training Guides</h1>
        <p class="page-title-sub">Publish protocols, workout directives, and nutrition blueprints for public visitor frontend</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('posts.index') }}" target="_blank" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span> Public Feed
        </a>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span> Write New Post
        </a>
    </div>
</div>

{{-- ── STAT CARDS ─────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Total Posts</div>
            <span class="material-symbols-outlined" style="font-size:22px;color:var(--color-on-surface);">article</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-on-surface);margin-top:6px;">
            {{ $stats['total'] }}
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Live on Website</div>
            <span class="material-symbols-outlined" style="font-size:22px;color:var(--color-primary-container);">check_circle</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-primary-container);margin-top:6px;">
            {{ $stats['published'] }}
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Drafts</div>
            <span class="material-symbols-outlined" style="font-size:22px;color:var(--color-secondary);">pending</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:36px;font-weight:700;color:var(--color-secondary);margin-top:6px;">
            {{ $stats['draft'] }}
        </div>
    </div>
</div>

{{-- ── FILTERS & SEARCH ───────────────────────────────────────── --}}
<div style="background:var(--color-surface-low);border-radius:12px;padding:16px 20px;border:1px solid var(--color-surface-highest);margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.posts.index') }}" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin:0;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;flex:1;min-width:280px;">
            {{-- Search Input --}}
            <div style="position:relative;flex:1;min-width:220px;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:16px;color:var(--color-outline);pointer-events:none;">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title or excerpt..." class="form-control-dark" style="padding-left:32px;font-size:13px;">
            </div>

            {{-- Status Filter --}}
            <select name="status" class="form-control-dark form-select-dark" style="width:auto;min-width:140px;">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            {{-- Category Filter --}}
            <select name="category_id" class="form-control-dark form-select-dark" style="width:auto;min-width:160px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;align-items:center;gap:8px;">
            <button type="submit" class="btn-primary" style="padding:9px 18px;font-size:12px;">Filter</button>
            @if(request()->hasAny(['q', 'status', 'category_id']))
            <a href="{{ route('admin.posts.index') }}" class="btn-ghost" style="padding:9px 14px;font-size:12px;">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- ── POSTS TABLE ────────────────────────────────────────────── --}}
<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;border:1px solid var(--color-surface-highest);">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Post Title & Excerpt</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Published At</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
            <tr>
                <td>
                    <div style="width:48px;height:48px;border-radius:6px;background:var(--color-surface-high);overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if($post->image_url)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span class="material-symbols-outlined" style="font-size:22px;color:var(--color-outline);">article</span>
                        @endif
                    </div>
                </td>
                <td style="max-width:320px;">
                    <a href="{{ route('admin.posts.show', $post) }}" style="color:var(--color-on-surface);text-decoration:none;font-weight:600;font-size:14px;line-height:1.3;display:block;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-on-surface)'">
                        {{ $post->title }}
                    </a>
                    @if($post->excerpt)
                        <div style="font-size:12px;color:var(--color-outline);margin-top:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $post->excerpt }}
                        </div>
                    @endif
                </td>
                <td>
                    <span class="badge-secondary">{{ $post->category->name ?? 'General' }}</span>
                </td>
                <td>
                    <div style="font-size:13px;font-weight:500;color:var(--color-on-surface);">{{ $post->author->name }}</div>
                    <div style="font-size:10px;font-family:var(--font-heading);letter-spacing:.05em;color:var(--color-outline);text-transform:uppercase;">
                        {{ $post->author->role === 'admin' ? 'Admin' : 'Trainer' }}
                    </div>
                </td>
                <td>
                    @if($post->status === 'published')
                        <span class="badge-active">Published</span>
                    @else
                        <span class="badge-paused">Draft</span>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--color-on-surface-variant);">
                    {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }}
                </td>
                <td style="text-align:right;">
                    <div style="display:inline-flex;align-items:center;gap:6px;justify-content:flex-end;">
                        {{-- Quick View / Preview --}}
                        <a href="{{ route('admin.posts.show', $post) }}" class="btn-ghost btn-sm btn-icon" title="Preview Post">
                            <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                        </a>

                        {{-- If published, link to public site --}}
                        @if($post->status === 'published')
                        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn-ghost btn-sm btn-icon" title="View Live on Website">
                            <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">open_in_new</span>
                        </a>
                        @endif

                        {{-- Quick Toggle Publish --}}
                        <form method="POST" action="{{ route('admin.posts.publish', $post) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm btn-icon" title="{{ $post->status === 'published' ? 'Move to Draft' : 'Publish Immediately' }}">
                                <span class="material-symbols-outlined" style="font-size:16px;color:{{ $post->status === 'published' ? 'var(--color-outline)' : 'var(--color-primary-container)' }};">
                                    {{ $post->status === 'published' ? 'unpublished' : 'publish' }}
                                </span>
                            </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost btn-sm btn-icon" title="Edit Post">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="margin:0;" onsubmit="return confirm('Delete post &quot;{{ addslashes($post->title) }}&quot; permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm btn-icon" title="Delete Post">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <span class="material-symbols-outlined" style="font-size:48px;">article</span>
                        <div class="empty-state-title">No Posts Found</div>
                        <div class="empty-state-body">
                            No posts match the criteria. Start by drafting a new training guide.
                        </div>
                        <a href="{{ route('admin.posts.create') }}" class="btn-primary" style="margin-top:16px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">add</span> Write First Post
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
    <div style="padding:16px;border-top:1px solid var(--color-surface-highest);">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
