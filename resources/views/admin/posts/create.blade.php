@extends('layouts.dashboard')
@section('title', 'Write Post')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">CONTENT</span></div>
        <h1 class="page-title">Write New Post</h1>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:700px;">
    <form method="POST" action="{{ route('admin.posts.store') }}">
    @csrf
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Title *</label>
                <input type="text" name="title" class="form-control-dark" value="{{ old('title') }}" required placeholder="Post title...">
            </div>
            <div>
                <label class="form-label-dark">Category</label>
                <select name="category_id" class="form-control-dark form-select-dark">
                    <option value="">No category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Tags <span style="color:var(--color-outline);font-size:11px;">(comma separated)</span></label>
                <input type="text" name="tags" class="form-control-dark" value="{{ old('tags') }}" placeholder="fitness, nutrition, strength">
            </div>
            <div>
                <label class="form-label-dark">Content *</label>
                <textarea name="body" class="form-control-dark" style="min-height:220px;resize:vertical;" required placeholder="Write your post here...">{{ old('body') }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Draft</button>
        <a href="{{ route('admin.posts.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
