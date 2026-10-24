@extends('layouts.dashboard')
@section('title', 'Create Diet Plan')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">NUTRITION</span></div>
        <h1 class="page-title">Create Diet Plan</h1>
    </div>
    <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">← Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;max-width:960px;">

    {{-- Left: Main Details --}}
    <form method="POST" action="{{ route('admin.diet-plans.store') }}" enctype="multipart/form-data" id="diet-create-form">
    @csrf
    <div class="card-dark" style="margin-bottom:16px;">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Plan Details</div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Plan Name <span style="color:var(--color-primary-container);">*</span></label>
                <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required placeholder="e.g. Lean Bulk Protocol">
                @error('name')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label-dark">Goal</label>
                <input type="text" name="goal" class="form-control-dark" value="{{ old('goal') }}" placeholder="e.g. Muscle Gain, Fat Loss, Maintenance">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label class="form-label-dark">Calories (kcal)</label>
                    <input type="number" name="total_calories" class="form-control-dark" value="{{ old('total_calories') }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Protein (g)</label>
                    <input type="number" name="protein_g" class="form-control-dark" value="{{ old('protein_g') }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Carbs (g)</label>
                    <input type="number" name="carbs_g" class="form-control-dark" value="{{ old('carbs_g') }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Fat (g)</label>
                    <input type="number" name="fat_g" class="form-control-dark" value="{{ old('fat_g') }}" min="0">
                </div>
            </div>
            <div>
                <label class="form-label-dark">Description</label>
                <textarea name="description" class="form-control-dark form-textarea-dark" placeholder="Plan overview...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">add</span> Create Plan</button>
        <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>

    {{-- Right: Cover Image --}}
    <div>
        <div class="card-dark">
            <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">image</span>
                Cover Image
            </div>

            {{-- Drop Zone --}}
            <div id="dp-create-dropzone"
                 onclick="document.getElementById('dp-create-input').click()"
                 ondragover="event.preventDefault();this.style.borderColor='var(--color-primary-container)';this.style.background='rgba(57,255,20,.06)'"
                 ondragleave="this.style.borderColor='var(--color-surface-highest)';this.style.background='var(--color-surface-container)'"
                 ondrop="dpCreateDrop(event)"
                 style="border:2px dashed var(--color-surface-highest);border-radius:10px;background:var(--color-surface-container);padding:24px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;margin-bottom:12px;">

                {{-- Preview (hidden initially) --}}
                <div id="dp-create-preview-wrap" style="display:none;position:relative;">
                    <img id="dp-create-preview-img" src="" alt="Preview" style="width:100%;height:160px;object-fit:cover;border-radius:8px;display:block;">
                    <button type="button" onclick="event.stopPropagation();dpCreateClear()" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.7);border:none;border-radius:50%;width:28px;height:28px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Remove">
                        <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                    </button>
                </div>

                {{-- Placeholder --}}
                <div id="dp-create-placeholder">
                    <span class="material-symbols-outlined" style="font-size:40px;color:var(--color-outline);display:block;margin-bottom:8px;">add_photo_alternate</span>
                    <div style="font-size:13px;color:var(--color-on-surface);font-weight:600;margin-bottom:4px;">Click or drag &amp; drop</div>
                    <div style="font-size:11px;color:var(--color-outline);">JPG, PNG, WebP · max 5 MB</div>
                </div>
            </div>

            <input type="file" id="dp-create-input" name="cover_image" form="diet-create-form" accept="image/*" style="display:none;" onchange="dpCreatePreview(this)">
            @error('cover_image')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror

            <div style="font-size:11px;color:var(--color-outline);">
                Shown on the diet plan card, member portal, and nutrition pages.
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function dpCreatePreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('dp-create-preview-img').src = e.target.result;
            document.getElementById('dp-create-preview-wrap').style.display = 'block';
            document.getElementById('dp-create-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function dpCreateDrop(e) {
    e.preventDefault();
    const dz = document.getElementById('dp-create-dropzone');
    dz.style.borderColor = 'var(--color-surface-highest)';
    dz.style.background   = 'var(--color-surface-container)';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('dp-create-input');
        input.files = dt.files;
        dpCreatePreview(input);
    }
}
function dpCreateClear() {
    document.getElementById('dp-create-input').value = '';
    document.getElementById('dp-create-preview-wrap').style.display = 'none';
    document.getElementById('dp-create-placeholder').style.display = 'block';
    document.getElementById('dp-create-preview-img').src = '';
}
</script>
@endpush
@endsection
