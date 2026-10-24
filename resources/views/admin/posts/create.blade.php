@extends('layouts.dashboard')
@section('title', 'Write Post — Admin')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">COMMUNITY & CONTENT</span></div>
        <h1 class="page-title">Create New Post</h1>
        <p class="page-title-sub">Publish gym announcements, training directives, and nutrition protocols to visitor frontend</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn-ghost">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to Posts
    </a>
</div>

@if($errors->any())
<div class="toast-dark error" style="margin-bottom:20px;">
    <span class="material-symbols-outlined" style="color:var(--color-error);flex-shrink:0;">error</span>
    <div>
        <div style="font-weight:600;margin-bottom:4px;">Please correct the errors below:</div>
        <ul style="margin:0;padding-left:18px;font-size:12px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">
        {{-- ── LEFT COLUMN: MAIN CONTENT ──────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card-dark">
                <div style="display:flex;flex-direction:column;gap:18px;">
                    <div>
                        <label class="form-label-dark">Post Title <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="text" name="title" class="form-control-dark" value="{{ old('title') }}" required placeholder="e.g. Master the Romanian Deadlift: Complete Hypertrophy Guide">
                        @error('title')<span style="font-size:12px;color:var(--color-error);">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Short Excerpt / Summary</label>
                        <textarea name="excerpt" class="form-control-dark" rows="3" placeholder="Brief 1-2 sentence teaser shown on post cards across the website...">{{ old('excerpt') }}</textarea>
                        <span style="font-size:11px;color:var(--color-outline);margin-top:4px;display:block;">If left empty, an automatic excerpt will be extracted from your content.</span>
                    </div>

                    <div>
                        <label class="form-label-dark">Post Body Content <span style="color:var(--color-primary-container);">*</span></label>
                        <textarea name="body" class="form-control-dark" style="min-height:360px;resize:vertical;font-family:inherit;font-size:14px;line-height:1.6;" required placeholder="Write your full guide here. Supports HTML formatting (e.g. <h2>, <h3>, <p>, <ul>, <li>, <blockquote>)...">{{ old('body') }}</textarea>
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
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Live immediately)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden from visitors)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-dark">Estimated Read Time (minutes)</label>
                        <input type="number" name="read_minutes" min="1" max="120" class="form-control-dark" value="{{ old('read_minutes') }}" placeholder="Auto-calculated if blank">
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">check</span> Create & Save Post
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="btn-ghost" style="width:100%;justify-content:center;">Cancel</a>
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
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                    Featured Image
                </div>

                {{-- Drag & Drop Zone --}}
                <div id="post-create-dropzone"
                     onclick="document.getElementById('post-create-img-input').click()"
                     ondragover="event.preventDefault();this.style.borderColor='var(--color-primary-container)';this.style.background='rgba(57,255,20,.06)'"
                     ondragleave="this.style.borderColor='var(--color-surface-highest)';this.style.background='var(--color-surface-container)'"
                     ondrop="postCreateDrop(event)"
                     style="border:2px dashed var(--color-surface-highest);border-radius:10px;background:var(--color-surface-container);padding:24px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;margin-bottom:12px;overflow:hidden;">

                    <div id="post-create-img-preview-wrap" style="display:none;position:relative;">
                        <img id="post-create-img-preview" src="" alt="Preview" style="width:100%;height:150px;object-fit:cover;border-radius:8px;display:block;">
                        <button type="button" onclick="event.stopPropagation();postCreateImgClear()" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.7);border:none;border-radius:50%;width:28px;height:28px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                        </button>
                    </div>
                    <div id="post-create-img-placeholder">
                        <span class="material-symbols-outlined" style="font-size:40px;color:var(--color-outline);display:block;margin-bottom:8px;">add_photo_alternate</span>
                        <div style="font-size:13px;color:var(--color-on-surface);font-weight:600;margin-bottom:4px;">Click or drag &amp; drop image</div>
                        <div style="font-size:11px;color:var(--color-outline);">JPG, PNG, WebP · max 5 MB</div>
                    </div>
                </div>
                <input type="file" id="post-create-img-input" name="featured_image" accept="image/*" style="display:none;" onchange="postCreateImgPreview(this)">
                @error('featured_image')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror

                <div style="text-align:center;font-size:11px;color:var(--color-outline);margin:10px 0;">— OR PASTE IMAGE URL —</div>

                <div>
                    <label class="form-label-dark">External Image URL</label>
                    <input type="url" name="featured_image_url" class="form-control-dark" value="{{ old('featured_image_url') }}" placeholder="https://images.unsplash.com/...">
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function postCreateImgPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('post-create-img-preview').src = e.target.result;
            document.getElementById('post-create-img-preview-wrap').style.display = 'block';
            document.getElementById('post-create-img-placeholder').style.display = 'none';
            document.getElementById('post-create-dropzone').style.padding = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function postCreateDrop(e) {
    e.preventDefault();
    const dz = document.getElementById('post-create-dropzone');
    dz.style.borderColor = 'var(--color-surface-highest)';
    dz.style.background   = 'var(--color-surface-container)';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        const input = document.getElementById('post-create-img-input');
        input.files = dt.files;
        postCreateImgPreview(input);
    }
}
function postCreateImgClear() {
    document.getElementById('post-create-img-input').value = '';
    document.getElementById('post-create-img-preview').src = '';
    document.getElementById('post-create-img-preview-wrap').style.display = 'none';
    document.getElementById('post-create-img-placeholder').style.display = 'block';
    document.getElementById('post-create-dropzone').style.padding = '24px';
}
</script>
@endpush
@endsection
