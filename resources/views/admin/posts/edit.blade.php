@extends('layouts.dashboard')
@section('title', 'Edit Post')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">CONTENT</span></div>
        <h1 class="page-title">Edit Post</h1>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:700px;">
    <form method="POST" action="{{ route('admin.posts.update', $post) }}">
    @csrf @method('PUT')
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Title *</label>
                <input type="text" name="title" class="form-control-dark" value="{{ old('title', $post->title) }}" required>
            </div>
            <div>
                <label class="form-label-dark">Category</label>
                <select name="category_id" class="form-control-dark form-select-dark">
                    <option value="">No category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id)==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Tags <span style="color:var(--color-outline);font-size:11px;">(comma separated)</span></label>
                <input type="text" name="tags" class="form-control-dark" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '') }}">
            </div>
            <div>
                <label class="form-label-dark">Content *</label>
                <textarea name="body" class="form-control-dark" style="min-height:220px;resize:vertical;" required>{{ old('body', $post->body) }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes</button>
        @if($post->status !== 'published')
        <form method="POST" action="{{ route('admin.posts.publish', $post) }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-ghost">Publish Now</button>
        </form>
        @endif
        <a href="{{ route('admin.posts.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
