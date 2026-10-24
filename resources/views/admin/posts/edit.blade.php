@extends('layouts.dashboard')
@section('title', 'Edit Post: ' . $post->title)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">COMMUNITY & CONTENT</span></div>
        <h1 class="page-title">Edit Post</h1>
        <p class="page-title-sub">Update content, adjust category, or change publication status</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        @if($post->status === 'published')
        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span> View Live Post
        </a>
        @endif
        <a href="{{ route('admin.posts.index') }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to Posts
        </a>
    </div>
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

<form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
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
                        <label class="form-label-dark">Short Excerpt / Summary</label>
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
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (Live on visitor frontend)</option>
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (Hidden from visitors)</option>
                        </select>
                    </div>

                    @if($post->published_at)
                    <div style="font-size:11px;color:var(--color-outline);">
                        Originally published: <span style="color:var(--color-on-surface);">{{ $post->published_at->format('d M Y, h:i A') }}</span>
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
                    Featured Image
                </div>

                {{-- Drag & Drop Zone --}}
                <div id="post-edit-dropzone"
                     onclick="document.getElementById('post-edit-img-input').click()"
                     ondragover="event.preventDefault();this.style.borderColor='var(--color-primary-container)';this.style.background='rgba(57,255,20,.06)'"
                     ondragleave="this.style.borderColor='var(--color-surface-highest)';this.style.background='var(--color-surface-container)'"
                     ondrop="postEditDrop(event)"
                     style="border:2px dashed var(--color-surface-highest);border-radius:10px;background:var(--color-surface-container);padding:{{ $post->image_url ? '0' : '24px' }};text-align:center;cursor:pointer;transition:border-color .2s,background .2s;margin-bottom:12px;overflow:hidden;">

                    <div id="post-edit-img-preview-wrap" style="display:{{ $post->image_url ? 'block' : 'none' }};position:relative;">
                        <img id="post-edit-img-preview" src="{{ $post->image_url ?? '' }}" alt="Preview" style="width:100%;height:150px;object-fit:cover;border-radius:8px;display:block;">
                        <button type="button" onclick="event.stopPropagation();postEditImgClear()" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.7);border:none;border-radius:50%;width:28px;height:28px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Remove / Change">
                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                        </button>
                    </div>
                    <div id="post-edit-img-placeholder" style="display:{{ $post->image_url ? 'none' : 'block' }};padding:{{ $post->image_url ? '0' : '0' }}">
                        <span class="material-symbols-outlined" style="font-size:40px;color:var(--color-outline);display:block;margin-bottom:8px;">add_photo_alternate</span>
                        <div style="font-size:13px;color:var(--color-on-surface);font-weight:600;margin-bottom:4px;">Click or drag &amp; drop image</div>
                        <div style="font-size:11px;color:var(--color-outline);">JPG, PNG, WebP · max 5 MB</div>
                    </div>
                </div>
                <input type="file" id="post-edit-img-input" name="featured_image" accept="image/*" style="display:none;" onchange="postEditImgPreview(this)">
                @error('featured_image')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror

                <div style="text-align:center;font-size:11px;color:var(--color-outline);margin:10px 0;">— OR PASTE IMAGE URL —</div>

                <div>
                    <label class="form-label-dark">External Image URL</label>
                    <input type="url" name="featured_image_url" class="form-control-dark" value="{{ old('featured_image_url', str_starts_with($post->featured_image ?? '', 'http') ? $post->featured_image : '') }}" placeholder="https://images.unsplash.com/...">
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Delete Form Separate to Avoid HTML nesting --}}
<div style="margin-top:24px;padding:20px;background:rgba(147,0,10,.1);border:1px solid rgba(255,180,171,.2);border-radius:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
    <div>
        <div style="font-family:var(--font-heading);font-size:13px;font-weight:700;color:var(--color-error);text-transform:uppercase;">Danger Zone</div>
        <div style="font-size:12px;color:var(--color-on-surface-variant);">Permanently delete this post and remove it from all public and administrative directories.</div>
    </div>
    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to permanently delete this post?');" style="margin:0;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger" style="padding:10px 18px;font-size:12px;">
            <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Delete Post
        </button>
    </form>
</div>

@push('scripts')
<script>
function postEditImgPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('post-edit-img-preview').src = e.target.result;
            document.getElementById('post-edit-img-preview-wrap').style.display = 'block';
            document.getElementById('post-edit-img-placeholder').style.display = 'none';
            document.getElementById('post-edit-dropzone').style.padding = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function postEditDrop(e) {
    e.preventDefault();
    const dz = document.getElementById('post-edit-dropzone');
    dz.style.borderColor = 'var(--color-surface-highest)';
    dz.style.background   = 'var(--color-surface-container)';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        const input = document.getElementById('post-edit-img-input');
        input.files = dt.files;
        postEditImgPreview(input);
    }
}
function postEditImgClear() {
    document.getElementById('post-edit-img-input').value = '';
    document.getElementById('post-edit-img-preview').src = '';
    document.getElementById('post-edit-img-preview-wrap').style.display = 'none';
    document.getElementById('post-edit-img-placeholder').style.display = 'block';
    document.getElementById('post-edit-dropzone').style.padding = '24px';
}
</script>
@endpush
@endsection
