@extends('layouts.dashboard')
@section('title', 'Edit Diet Plan: ' . $plan->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">NUTRITION</span></div>
        <h1 class="page-title">Edit: {{ $plan->name }}</h1>
    </div>
    <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">← Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;max-width:960px;">

    {{-- Left: Main Details --}}
    <form method="POST" action="{{ route('admin.diet-plans.update', $plan) }}" enctype="multipart/form-data" id="diet-edit-form">
    @csrf @method('PUT')
    <div class="card-dark" style="margin-bottom:16px;">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Plan Details</div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Plan Name <span style="color:var(--color-primary-container);">*</span></label>
                <input type="text" name="name" class="form-control-dark" value="{{ old('name', $plan->name) }}" required>
                @error('name')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label-dark">Goal</label>
                <input type="text" name="goal" class="form-control-dark" value="{{ old('goal', $plan->goal) }}">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label class="form-label-dark">Calories (kcal)</label>
                    <input type="number" name="total_calories" class="form-control-dark" value="{{ old('total_calories', $plan->total_calories) }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Protein (g)</label>
                    <input type="number" name="protein_g" class="form-control-dark" value="{{ old('protein_g', $plan->total_protein_g) }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Carbs (g)</label>
                    <input type="number" name="carbs_g" class="form-control-dark" value="{{ old('carbs_g', $plan->total_carbs_g) }}" min="0">
                </div>
                <div>
                    <label class="form-label-dark">Fat (g)</label>
                    <input type="number" name="fat_g" class="form-control-dark" value="{{ old('fat_g', $plan->total_fat_g) }}" min="0">
                </div>
            </div>
            <div>
                <label class="form-label-dark">Description</label>
                <textarea name="description" class="form-control-dark form-textarea-dark">{{ old('description', $plan->description) }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes</button>
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
            <div id="dp-edit-dropzone"
                 onclick="document.getElementById('dp-edit-input').click()"
                 ondragover="event.preventDefault();this.style.borderColor='var(--color-primary-container)';this.style.background='rgba(57,255,20,.06)'"
                 ondragleave="this.style.borderColor='var(--color-surface-highest)';this.style.background='var(--color-surface-container)'"
                 ondrop="dpEditDrop(event)"
                 style="border:2px dashed var(--color-surface-highest);border-radius:10px;background:var(--color-surface-container);padding:{{ $plan->cover_image_url ? '0' : '24px' }};text-align:center;cursor:pointer;transition:border-color .2s,background .2s;margin-bottom:12px;overflow:hidden;">

                {{-- Existing or new preview --}}
                <div id="dp-edit-preview-wrap" style="display:{{ $plan->cover_image_url ? 'block' : 'none' }};position:relative;">
                    <img id="dp-edit-preview-img" src="{{ $plan->cover_image_url ?? '' }}" alt="Cover" style="width:100%;height:160px;object-fit:cover;border-radius:8px;display:block;">
                    <button type="button" onclick="event.stopPropagation();dpEditClear()" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.7);border:none;border-radius:50%;width:28px;height:28px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Remove">
                        <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                    </button>
                </div>

                {{-- Placeholder --}}
                <div id="dp-edit-placeholder" style="display:{{ $plan->cover_image_url ? 'none' : 'block' }};padding:24px 0;">
                    <span class="material-symbols-outlined" style="font-size:40px;color:var(--color-outline);display:block;margin-bottom:8px;">add_photo_alternate</span>
                    <div style="font-size:13px;color:var(--color-on-surface);font-weight:600;margin-bottom:4px;">Click or drag &amp; drop</div>
                    <div style="font-size:11px;color:var(--color-outline);">JPG, PNG, WebP · max 5 MB</div>
                </div>
            </div>

            <input type="file" id="dp-edit-input" name="cover_image" form="diet-edit-form" accept="image/*" style="display:none;" onchange="dpEditPreview(this)">
            <input type="hidden" name="remove_image" id="dp-edit-remove-input" form="diet-edit-form" value="0">
            @error('cover_image')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror

            @if($plan->cover_image)
            <button type="button" onclick="dpEditRemoveSaved()" id="dp-edit-remove-btn"
                style="width:100%;margin-top:8px;padding:6px;border-radius:6px;background:rgba(147,0,10,.12);border:1px solid rgba(255,180,171,.2);color:var(--color-error);cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center;gap:6px;">
                <span class="material-symbols-outlined" style="font-size:15px;">delete</span> Remove Current Image
            </button>
            @endif

            <div style="font-size:11px;color:var(--color-outline);margin-top:8px;">
                Shown on diet plan cards and the member portal nutrition section.
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function dpEditPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('dp-edit-preview-img').src = e.target.result;
            document.getElementById('dp-edit-preview-wrap').style.display = 'block';
            document.getElementById('dp-edit-placeholder').style.display = 'none';
            document.getElementById('dp-edit-dropzone').style.padding = '0';
            document.getElementById('dp-edit-remove-input').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function dpEditDrop(e) {
    e.preventDefault();
    const dz = document.getElementById('dp-edit-dropzone');
    dz.style.borderColor = 'var(--color-surface-highest)';
    dz.style.background   = 'var(--color-surface-container)';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('dp-edit-input');
        input.files = dt.files;
        dpEditPreview(input);
    }
}
function dpEditClear() {
    document.getElementById('dp-edit-input').value = '';
    document.getElementById('dp-edit-preview-wrap').style.display = 'none';
    document.getElementById('dp-edit-placeholder').style.display = 'block';
    document.getElementById('dp-edit-preview-img').src = '';
    document.getElementById('dp-edit-dropzone').style.padding = '24px';
    document.getElementById('dp-edit-remove-input').value = '0';
}
function dpEditRemoveSaved() {
    document.getElementById('dp-edit-remove-input').value = '1';
    document.getElementById('dp-edit-input').value = '';
    document.getElementById('dp-edit-preview-img').src = '';
    document.getElementById('dp-edit-preview-wrap').style.display = 'none';
    document.getElementById('dp-edit-placeholder').style.display = 'block';
    document.getElementById('dp-edit-dropzone').style.padding = '24px';
    const btn = document.getElementById('dp-edit-remove-btn');
    if (btn) { btn.style.opacity = '.5'; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:15px;">done</span> Marked for Removal'; }
}
</script>
@endpush
@endsection
