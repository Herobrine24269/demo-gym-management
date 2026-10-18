@extends('layouts.dashboard')
@section('title', 'Create Plan')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">PLANS</span></div>
        <h1 class="page-title">Create Membership Plan</h1>
    </div>
    <a href="{{ route('admin.plans.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:560px;">
    <form method="POST" action="{{ route('admin.plans.store') }}">
    @csrf
    <div class="card-dark">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div><label class="form-label-dark">Plan Name *</label><input type="text" name="name" class="form-control-dark" value="{{ old('name') }}" required placeholder="e.g. Monthly, Annual"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label-dark">Duration (days) *</label><input type="number" name="duration_days" class="form-control-dark" value="{{ old('duration_days') }}" required min="1"></div>
                <div><label class="form-label-dark">Price (PKR) *</label><input type="number" name="price" class="form-control-dark" value="{{ old('price') }}" required min="0" step="0.01"></div>
            </div>
            <div>
                <label class="form-label-dark">Features (one per line)</label>
                <textarea name="features" class="form-control-dark form-textarea-dark" placeholder="Full gym access&#10;Locker room&#10;Free towel">{{ old('features') }}</textarea>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:6px;background:var(--color-surface-container);">
                <input type="checkbox" name="is_active" value="1" checked id="active" style="accent-color:var(--color-primary-container);">
                <label for="active" style="font-size:13px;color:var(--color-on-surface);cursor:pointer;">Active (visible to reception)</label>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">add</span> Create Plan</button>
        <a href="{{ route('admin.plans.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
