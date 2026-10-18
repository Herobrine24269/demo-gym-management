@extends('layouts.dashboard')
@section('title', 'Edit Member')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">Edit: {{ $member->user->name }}</h1>
    </div>
    <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost">← Back</a>
</div>

<div style="max-width:720px;">
    <form method="POST" action="{{ route('admin.members.update', $member) }}">
        @csrf @method('PUT')
        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Edit Profile</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label class="form-label-dark">Full Name *</label>
                    <input type="text" name="name" class="form-control-dark" value="{{ old('name', $member->user->name) }}" required>
                </div>
                <div>
                    <label class="form-label-dark">Phone</label>
                    <input type="text" name="phone" class="form-control-dark" value="{{ old('phone', $member->user->phone) }}">
                </div>
                <div>
                    <label class="form-label-dark">Gender</label>
                    <select name="gender" class="form-control-dark form-select-dark">
                        <option value="">Select...</option>
                        @foreach(['male','female','other'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $member->gender)==$g ? 'selected':'' }}>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label-dark">Date of Birth</label>
                    <input type="date" name="dob" class="form-control-dark" value="{{ old('dob', $member->dob?->format('Y-m-d')) }}">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Address</label>
                    <textarea name="address" class="form-control-dark form-textarea-dark">{{ old('address', $member->address) }}</textarea>
                </div>
                <div>
                    <label class="form-label-dark">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control-dark" value="{{ old('emergency_contact', $member->emergency_contact) }}">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Health Notes</label>
                    <textarea name="health_notes" class="form-control-dark form-textarea-dark">{{ old('health_notes', $member->health_notes) }}</textarea>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:16px;">
            <button type="submit" class="btn-primary">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes
            </button>
            <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
