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
    <form method="POST" action="{{ route('admin.trainers.store') }}">
    @csrf
    <div class="card-dark">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Trainer Details</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Full Name *</label>
                <input type="text" name="name" class="form-control-dark" value="{{ old('name') }}" required>
            </div>
            <div>
                <label class="form-label-dark">Email *</label>
                <input type="email" name="email" class="form-control-dark" value="{{ old('email') }}" required>
            </div>
            <div>
                <label class="form-label-dark">Phone</label>
                <input type="text" name="phone" class="form-control-dark" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="form-label-dark">Password *</label>
                <input type="password" name="password" class="form-control-dark" required placeholder="Min. 6 characters">
            </div>
            <div>
                <label class="form-label-dark">Hire Date *</label>
                <input type="date" name="hired_at" class="form-control-dark" value="{{ old('hired_at', today()->toDateString()) }}" required>
            </div>
            <div style="grid-column:1/-1;">
                <label class="form-label-dark">Specialization</label>
                <input type="text" name="specialization" class="form-control-dark" value="{{ old('specialization') }}" placeholder="e.g. Strength & Conditioning">
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
@endsection
