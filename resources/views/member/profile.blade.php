@extends('layouts.dashboard')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBER PORTAL</span></div>
        <h1 class="page-title">My Profile</h1>
    </div>
</div>
<div style="max-width:560px;">
    <div class="card-dark">
        <div style="margin-bottom:24px;">
            <div class="section-title" style="font-size:16px;margin-bottom:4px;">Update Profile</div>
            <p style="font-size:12px;color:var(--color-outline);">Update your name, phone number, or change your password.</p>
        </div>
        <form method="POST" action="{{ route('member.profile.update') }}">
            @csrf @method('PUT')
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Full Name</label>
                <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Email (read only)</label>
                <input type="email" class="form-control-dark" value="{{ $user->email }}" disabled style="opacity:.6;cursor:not-allowed;">
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Phone</label>
                <input type="text" name="phone" class="form-control-dark" value="{{ old('phone', $user->phone) }}" placeholder="+92-300-0000000">
            </div>
            <div class="divider"></div>
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="••••••••" autocomplete="new-password">
                @error('password')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
            </div>
            <div style="margin-bottom:24px;">
                <label class="form-label-dark">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control-dark" placeholder="••••••••" autocomplete="new-password">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
