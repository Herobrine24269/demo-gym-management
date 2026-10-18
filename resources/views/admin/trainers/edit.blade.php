@extends('layouts.dashboard')
@section('title', 'Edit Trainer')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">STAFF</span></div>
        <h1 class="page-title">Edit: {{ $trainer->user->name }}</h1>
    </div>
    <a href="{{ route('admin.trainers.show', $trainer) }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:640px;">
    <form method="POST" action="{{ route('admin.trainers.update', $trainer) }}">
    @csrf @method('PUT')
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Edit Trainer Profile</div>
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
@endsection
