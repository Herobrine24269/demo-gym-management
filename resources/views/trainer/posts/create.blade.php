@extends('layouts.dashboard')
@section('title', 'Write Post — Trainer Portal')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER PORTAL</span></div>
        <h1 class="page-title">Write New Training Guide</h1>
        <p class="page-title-sub">Publish educational posts, form breakdowns, and nutritional frameworks for visitors and members</p>
    </div>
    <a href="{{ route('trainer.posts.index') }}" class="btn-ghost">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to My Posts
    </a>
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

<form method="POST" action="{{ route('trainer.posts.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">
        {{-- ── LEFT COLUMN: MAIN CONTENT ──────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card-dark">
                <div style="display:flex;flex-direction:column;gap:18px;">
                    <div>
                        <label class="form-label-dark">Post Title <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="text" name="title" class="form-control-dark" value="{{ old('title') }}" required placeholder="e.g. 5 Biomechanical Fixes for Squat Depth & Hip Shift">
                        @error('title')<span style="font-size:12px;color:var(--color-error);">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Short Excerpt / Teaser</label>
                        <textarea name="excerpt" class="form-control-dark" rows="3" placeholder="Brief summary displayed on the card previews across the visitor frontend...">{{ old('excerpt') }}</textarea>
                        <span style="font-size:11px;color:var(--color-outline);margin-top:4px;display:block;">If left empty, an automatic excerpt will be generated from your body text.</span>
                    </div>

                    <div>
                        <label class="form-label-dark">Post Content <span style="color:var(--color-primary-container);">*</span></label>
                        <textarea name="body" class="form-control-dark" style="min-height:360px;resize:vertical;font-family:inherit;font-size:14px;line-height:1.6;" required placeholder="Write your full guide or article. You can use standard formatting or HTML tags (e.g. <h2>, <h3>, <p>, <ul>, <li>, <blockquote>)...">{{ old('body') }}</textarea>
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
                        <label class="form-label-dark">Status <span style="color:var(--color-primary-container);">*</span></label>
                        <select name="status" class="form-control-dark form-select-dark" required>
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Live on visitor website)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Private, not visible to visitors)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-dark">Estimated Read Time (minutes)</label>
                        <input type="number" name="read_minutes" min="1" max="120" class="form-control-dark" value="{{ old('read_minutes') }}" placeholder="Calculated automatically if empty">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">check</span> Save & Publish Post
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
                        <label class="form-label-dark">Choose Category</label>
                        <select name="category_id" class="form-control-dark form-select-dark">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="text-align:center;font-size:11px;color:var(--color-outline);">— OR CREATE NEW —</div>

                    <div>
                        <label class="form-label-dark">New Category Name</label>
                        <input type="text" name="new_category" class="form-control-dark" value="{{ old('new_category') }}" placeholder="e.g. Injury Prevention">
                    </div>
                </div>
            </div>

            {{-- Featured Image Box --}}
            <div class="card-dark">
                <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">image</span>
                    Featured Cover Photo
                </div>

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <label class="form-label-dark">Upload Cover File</label>
                        <input type="file" name="featured_image" accept="image/*" class="form-control-dark" style="padding:8px;">
                        <span style="font-size:11px;color:var(--color-outline);margin-top:4px;display:block;">JPG, PNG, WebP up to 5MB.</span>
                    </div>

                    <div style="text-align:center;font-size:11px;color:var(--color-outline);">— OR IMAGE URL —</div>

                    <div>
                        <label class="form-label-dark">Image Web URL</label>
                        <input type="url" name="featured_image_url" class="form-control-dark" value="{{ old('featured_image_url') }}" placeholder="https://images.unsplash.com/...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
