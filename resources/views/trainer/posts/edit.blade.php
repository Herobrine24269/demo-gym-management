@extends('layouts.dashboard')
@section('title', 'Edit Post: ' . $post->title)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">Edit Training Post</h1>
        <p class="page-title-sub">Update post details, modify status, or refine your instructions</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        @if($post->status === 'published')
        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span> View Live Post
        </a>
        @endif
        <a href="{{ route('trainer.posts.index') }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to My Posts
        </a>
    </div>
</div>

@if($errors->any())
<div class="toast-dark error" style="margin-bottom:20px;">
    <span class="material-symbols-outlined" style="color:var(--color-error);flex-shrink:0;">error</span>
    <div>
        <div style="font-weight:600;margin-bottom:4px;">Please fix the following errors:</div>
        <ul style="margin:0;padding-left:18px;font-size:12px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('trainer.posts.update', $post) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">
        {{-- ── LEFT COLUMN: MAIN CONTENT ──────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card-dark">
                <div style="display:flex;flex-direction:column;gap:18px;">
                    <div>
                        <label class="form-label-dark">Post Title <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="text" name="title" class="form-control-dark" value="{{ old('title', $post->title) }}" required>
                        @error('title')<span style="font-size:12px;color:var(--color-error);">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Short Excerpt / Teaser</label>
                        <textarea name="excerpt" class="form-control-dark" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label-dark">Post Body Content <span style="color:var(--color-primary-container);">*</span></label>
                        <textarea name="body" class="form-control-dark" style="min-height:380px;resize:vertical;font-family:inherit;font-size:14px;line-height:1.6;" required>{{ old('body', $post->body) }}</textarea>
                        @error('body')<span style="font-size:12px;color:var(--color-error);">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT COLUMN: SETTINGS & PUBLISHING ─────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- Publish Box --}}
            <div class="card-dark">
                <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">send</span>
                    Publish Settings
                </div>

                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="form-label-dark">Publication Status <span style="color:var(--color-primary-container);">*</span></label>
                        <select name="status" class="form-control-dark form-select-dark" required>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (Live on website)</option>
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                        </select>
                    </div>

                    @if($post->published_at)
                    <div style="font-size:11px;color:var(--color-outline);">
                        Published on: <span style="color:var(--color-on-surface);">{{ $post->published_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @endif

                    <div>
                        <label class="form-label-dark">Estimated Read Time (minutes)</label>
                        <input type="number" name="read_minutes" min="1" max="120" class="form-control-dark" value="{{ old('read_minutes', $post->read_minutes) }}">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes
                        </button>
                        <a href="{{ route('trainer.posts.index') }}" class="btn-ghost" style="width:100%;justify-content:center;">Cancel</a>
                    </div>
                </div>
            </div>

            {{-- Category Box --}}
            <div class="card-dark">
                <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-secondary);">category</span>
                    Category
                </div>

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="form-label-dark">Select Category</label>
                        <select name="category_id" class="form-control-dark form-select-dark">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="text-align:center;font-size:11px;color:var(--color-outline);">— OR CREATE NEW —</div>

                    <div>
                        <label class="form-label-dark">New Category Name</label>
                        <input type="text" name="new_category" class="form-control-dark" value="{{ old('new_category') }}" placeholder="e.g. Mobility & Flexibility">
                    </div>
                </div>
            </div>

            {{-- Featured Image Box --}}
            <div class="card-dark">
                <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">image</span>
                    Featured Cover Photo
                </div>

                @if($post->image_url)
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px;color:var(--color-outline);margin-bottom:6px;">Current Cover:</div>
                    <div style="border-radius:6px;overflow:hidden;border:1px solid var(--color-surface-highest);max-height:160px;">
                        <img src="{{ $post->image_url }}" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:block;">
                    </div>
                </div>
                @endif

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="form-label-dark">Upload Replacement File</label>
                        <input type="file" name="featured_image" accept="image/*" class="form-control-dark" style="padding:8px;">
                    </div>

                    <div style="text-align:center;font-size:11px;color:var(--color-outline);">— OR IMAGE URL —</div>

                    <div>
                        <label class="form-label-dark">Image Web URL</label>
                        <input type="url" name="featured_image_url" class="form-control-dark" value="{{ old('featured_image_url', str_starts_with($post->featured_image ?? '', 'http') ? $post->featured_image : '') }}" placeholder="https://images.unsplash.com/...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Delete Form Separate to Avoid HTML nesting --}}
<div style="margin-top:24px;padding:20px;background:rgba(147,0,10,.1);border:1px solid rgba(255,180,171,.2);border-radius:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
    <div>
        <div style="font-family:var(--font-heading);font-size:13px;font-weight:700;color:var(--color-error);text-transform:uppercase;">Delete Post</div>
        <div style="font-size:12px;color:var(--color-on-surface-variant);">Permanently remove this training post from your portal and the visitor website.</div>
    </div>
    <form method="POST" action="{{ route('trainer.posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to permanently delete this post?');" style="margin:0;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger" style="padding:10px 18px;font-size:12px;">
            <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Delete Post
        </button>
    </form>
</div>
@endsection
