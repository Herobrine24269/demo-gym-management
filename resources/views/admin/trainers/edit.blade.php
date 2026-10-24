@extends('layouts.dashboard')
@section('title', 'Edit Trainer: ' . $trainer->user->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">STAFF</span></div>
        <h1 class="page-title">Edit: {{ $trainer->user->name }}</h1>
    </div>
    <a href="{{ route('admin.trainers.show', $trainer) }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:640px;">
    <form method="POST" action="{{ route('admin.trainers.update', $trainer) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Edit Trainer Profile</div>

        {{-- Profile Photo Section --}}
        <div style="display:flex;align-items:center;gap:20px;padding:16px;background:var(--color-surface-container);border-radius:8px;border:1px solid var(--color-surface-highest);margin-bottom:20px;flex-wrap:wrap;">
            <div style="position:relative;">
                <div id="trainer-edit-avatar-box" style="width:80px;height:80px;border-radius:50%;overflow:hidden;background:var(--color-surface-high);border:2px solid var(--color-primary-container);box-shadow:var(--glow-green);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($trainer->user->avatar_url)
                        <img id="trainer-edit-avatar-img" src="{{ $trainer->user->avatar_url }}" alt="{{ $trainer->user->name }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <img id="trainer-edit-avatar-img" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                        <span id="trainer-edit-avatar-initials" style="font-family:var(--font-heading);font-size:28px;font-weight:700;color:var(--color-primary-container);">
                            {{ $trainer->user->initials() }}
                        </span>
                    @endif
                </div>
            </div>
            <div style="flex:1;min-width:220px;">
                <div style="font-family:var(--font-heading);font-size:13px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:4px;">Profile Picture</div>
                <div style="font-size:11px;color:var(--color-outline);margin-bottom:10px;">PNG, JPG or WebP. Up to 4MB. Recommended: square photo.</div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <label for="trainer-edit-avatar-input" class="btn-primary btn-sm" style="cursor:pointer;margin:0;">
                        <span class="material-symbols-outlined" style="font-size:16px;">upload</span> Upload New Photo
                    </label>
                    <input type="file" id="trainer-edit-avatar-input" name="avatar" accept="image/*" style="display:none;" onchange="previewTrainerEditAvatar(this)">

                    @if($trainer->user->avatar)
                    <button type="button" class="btn-ghost btn-sm" onclick="removeTrainerAvatar()" id="remove-trainer-avatar-btn" style="color:var(--color-error);">
                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Remove Photo
                    </button>
                    <input type="hidden" name="remove_avatar" id="remove-trainer-avatar-input" value="0">
                    @endif
                </div>
                @error('avatar')<div style="font-size:12px;color:var(--color-error);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div>
                <label class="form-label-dark">Full Name *</label>
                <input type="text" name="name" class="form-control-dark" value="{{ old('name', $trainer->user->name) }}" required>
            </div>
            <div>
                <label class="form-label-dark">Phone</label>
                <input type="text" name="phone" class="form-control-dark" value="{{ old('phone', $trainer->user->phone) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Specialization</label>
                <input type="text" name="specialization" class="form-control-dark" value="{{ old('specialization', $trainer->specialization) }}">
            </div>
            <div>
                <label class="form-label-dark">Monthly Salary (PKR)</label>
                <input type="number" name="salary" class="form-control-dark" value="{{ old('salary', $trainer->salary) }}" min="0">
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:6px;background:var(--color-surface-container);">
                <input type="checkbox" name="is_active" value="1" {{ $trainer->is_active ? 'checked' : '' }} id="is_active" style="accent-color:var(--color-primary-container);">
                <label for="is_active" style="font-size:13px;color:var(--color-on-surface);cursor:pointer;">Active / Available</label>
            </div>
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Bio</label>
                <textarea name="bio" class="form-control-dark form-textarea-dark">{{ old('bio', $trainer->bio) }}</textarea>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;">
        <button type="submit" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes
        </button>
        <a href="{{ route('admin.trainers.show', $trainer) }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>

@push('scripts')
<script>
function previewTrainerEditAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img      = document.getElementById('trainer-edit-avatar-img');
            const initials = document.getElementById('trainer-edit-avatar-initials');
            img.src = e.target.result;
            img.style.display = 'block';
            if (initials) initials.style.display = 'none';
            const removeInput = document.getElementById('remove-trainer-avatar-input');
            if (removeInput) removeInput.value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeTrainerAvatar() {
    if (confirm('Remove profile photo for this trainer?')) {
        const removeInput = document.getElementById('remove-trainer-avatar-input');
        if (removeInput) removeInput.value = '1';
        const img      = document.getElementById('trainer-edit-avatar-img');
        const initials = document.getElementById('trainer-edit-avatar-initials');
        if (img) img.style.display = 'none';
        if (initials) initials.style.display = 'block';
        const fileInput = document.getElementById('trainer-edit-avatar-input');
        if (fileInput) fileInput.value = '';
        const btn = document.getElementById('remove-trainer-avatar-btn');
        if (btn) { btn.style.opacity = '0.5'; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">done</span> Marked for Removal'; }
    }
}
</script>
@endpush
@endsection
