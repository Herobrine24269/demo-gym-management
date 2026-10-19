@extends('layouts.dashboard')
@section('title', 'Posts & Guides')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">CONTENT</span></div>
        <h1 class="page-title">Posts & Guides</h1>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> New Post
    </a>
</div>

<div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-dark">
            <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Published</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
            @forelse($posts as $post)
            <tr>
                <td>
                    <div class="td-name">{{ $post->title }}</div>
                    @if($post->excerpt)<div class="td-sub">{{ Str::limit($post->excerpt, 60) }}</div>@endif
                </td>
                <td><span class="badge-secondary">{{ $post->category->name ?? '—' }}</span></td>
                <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $post->author->name }}</td>
                <td>
                    @if($post->status === 'published') <span class="badge-active">Published</span>
                    @else <span class="badge-paused">Draft</span> @endif
                </td>
                <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $post->published_at?->format('d M Y') ?? '—' }}</td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:4px;justify-content:flex-end;">
                        @if($post->status !== 'published')
                        <form method="POST" action="{{ route('admin.posts.publish', $post) }}">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm" title="Publish">
                                <span class="material-symbols-outlined" style="font-size:16px;">publish</span>
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><span class="material-symbols-outlined">article</span><div class="empty-state-title">No Posts Yet</div><div class="empty-state-body"><a href="{{ route('admin.posts.create') }}" style="color:var(--color-primary-container);">Write first post →</a></div></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())<div style="padding:16px;">{{ $posts->links() }}</div>@endif
</div>
@endsection
