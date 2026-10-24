@extends('layouts.dashboard')
@section('title', 'Add Trainer')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">STAFF</span></div>
        <h1 class="page-title">Add Trainer</h1>
    </div>
    <a href="{{ route('admin.trainers.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:640px;">
    <form method="POST" action="{{ route('admin.trainers.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Trainer Details</div>

        {{-- Profile Photo Upload --}}
        <div style="display:flex;align-items:center;gap:16px;padding:14px 16px;background:var(--color-surface-container);border-radius:8px;border:1px solid var(--color-surface-highest);margin-bottom:20px;">
            <div id="trainer-avatar-box" style="width:72px;height:72px;border-radius:50%;overflow:hidden;background:var(--color-surface-high);border:2px solid var(--color-primary-container);box-shadow:var(--glow-green);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <img id="trainer-avatar-img" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                <span id="trainer-avatar-icon" class="material-symbols-outlined" style="font-size:28px;color:var(--color-outline);">person</span>
            </div>
            <div style="flex:1;">
                <div style="font-family:var(--font-heading);font-size:13px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:4px;">
                    Profile Photo <span style="color:var(--color-outline);font-size:11px;text-transform:none;">(Optional)</span>
                </div>
                <div style="font-size:11px;color:var(--color-outline);margin-bottom:10px;">Upload trainer photo for profile, member roster &amp; home page showcase.</div>
                <label for="trainer-avatar-input" class="btn-primary btn-sm" style="cursor:pointer;margin:0;">
                    <span class="material-symbols-outlined" style="font-size:16px;">upload</span> Choose Photo
                </label>
                <input type="file" id="trainer-avatar-input" name="avatar" accept="image/*" style="display:none;" onchange="previewTrainerAvatar(this)">
                @error('avatar')<div style="font-size:12px;color:var(--color-error);margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Full Name *</label>
                <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label-dark">Email *</label>
                <input type="email" name="email" class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label-dark">Phone</label>
                <input type="text" name="phone" class="form-control-dark" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="form-label-dark">Password *</label>
                <input type="password" name="password" class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}" required placeholder="Min. 6 characters">
                @error('password')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label-dark">Hire Date *</label>
                <input type="date" name="hired_at" class="form-control-dark" value="{{ old('hired_at', today()->toDateString()) }}" required>
            </div>
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Specialization</label>
                <input type="text" name="specialization" class="form-control-dark" value="{{ old('specialization') }}" placeholder="e.g. Strength &amp; Conditioning">
            </div>
            <div>
                <label class="form-label-dark">Monthly Salary (PKR)</label>
                <input type="number" name="salary" class="form-control-dark" value="{{ old('salary') }}" min="0">
            </div>
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Bio</label>
                <textarea name="bio" class="form-control-dark form-textarea-dark" placeholder="Brief description of experience...">{{ old('bio') }}</textarea>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;">
        <button type="submit" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">person_add</span> Add Trainer
        </button>
        <a href="{{ route('admin.trainers.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>

@push('scripts')
<script>
function previewTrainerAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img  = document.getElementById('trainer-avatar-img');
            const icon = document.getElementById('trainer-avatar-icon');
            img.src = e.target.result;
            img.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
