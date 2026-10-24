@extends('layouts.dashboard')
@section('title', 'My Profile — Member Portal')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBER PORTAL</span></div>
        <h1 class="page-title">My Account Profile</h1>
        <p class="page-title-sub">Manage your personal credentials, contact phone, and profile photo</p>
    </div>
</div>

<div style="max-width:680px;">
    <div class="card-dark">
        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── PROFILE PHOTO SECTION ────────────────────────── --}}
            <div style="padding-bottom:24px;margin-bottom:24px;border-bottom:1px solid var(--color-surface-highest);">
                <div class="section-title" style="font-size:15px;margin-bottom:6px;">Profile Picture</div>
                <p style="font-size:12px;color:var(--color-outline);margin:0 0 16px;">Upload a clear photo for your gym account and check-in roster.</p>

                <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                    {{-- Avatar Preview Box --}}
                    <div style="position:relative;">
                        <div id="avatar-preview-box" style="width:84px;height:84px;border-radius:50%;overflow:hidden;background:var(--color-surface-container);border:2px solid var(--color-primary-container);box-shadow:var(--glow-green);display:flex;align-items:center;justify-content:center;">
                            @if($user->avatar_url)
                                <img id="avatar-img-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <img id="avatar-img-preview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                                <span id="avatar-initials-fallback" style="font-family:var(--font-heading);font-size:32px;font-weight:700;color:var(--color-primary-container);">
                                    {{ $user->initials() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Controls --}}
                    <div style="flex:1;min-width:220px;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px;">
                            <label for="avatar-input" class="btn-primary btn-sm" style="cursor:pointer;margin:0;">
                                <span class="material-symbols-outlined" style="font-size:16px;">upload</span> Upload New Photo
                            </label>
                            <input type="file" id="avatar-input" name="avatar" accept="image/png,image/jpeg,image/webp" style="display:none;" onchange="previewAvatar(this)">

                            @if($user->avatar)
                            <button type="button" class="btn-ghost btn-sm" onclick="markRemoveAvatar()" id="remove-avatar-btn" style="color:var(--color-error);">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Remove Photo
                            </button>
                            <input type="hidden" name="remove_avatar" id="remove-avatar-input" value="0">
                            @endif
                        </div>
                        <div style="font-size:11px;color:var(--color-outline);">
                            PNG, JPG, or WebP up to 4MB. Square photo recommended.
                        </div>
                        @error('avatar')
                            <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── BASIC CREDENTIALS ────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:24px;">
                <div class="section-title" style="font-size:15px;margin-bottom:2px;">Contact Information</div>

                <div>
                    <label class="form-label-dark">Full Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark" style="display:flex;align-items:center;justify-content:space-between;">
                        <span>Email Address</span>
                        <span style="font-size:10px;color:var(--color-outline);text-transform:none;letter-spacing:normal;">Managed exclusively by Gym Admin</span>
                    </label>
                    <input type="email" class="form-control-dark" value="{{ $user->email }}" disabled style="opacity:.65;cursor:not-allowed;background:var(--color-surface-low);">
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">
                        Need to change your official login email? Please ask front desk staff or facility management.
                    </div>
                </div>

                <div>
                    <label class="form-label-dark">Phone Number</label>
                    <input type="text" name="phone" class="form-control-dark {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone', $user->phone) }}" placeholder="+92-300-1234567">
                    @error('phone')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="divider"></div>

            {{-- ── SECURITY & PASSWORD ──────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div>
                    <div class="section-title" style="font-size:15px;margin-bottom:2px;">Change Password</div>
                    <p style="font-size:12px;color:var(--color-outline);margin:0;">Leave empty if you wish to keep your current password.</p>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label-dark">New Password</label>
                        <input type="password" name="password" class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="••••••••" autocomplete="new-password">
                        @error('password')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control-dark" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:13px;">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Profile Changes
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('avatar-img-preview');
            const fallback = document.getElementById('avatar-initials-fallback');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            if (fallback) fallback.style.display = 'none';

            const removeInput = document.getElementById('remove-avatar-input');
            if (removeInput) removeInput.value = '0';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function markRemoveAvatar() {
    if (confirm('Remove your profile photo? It will reset to your initials.')) {
        const removeInput = document.getElementById('remove-avatar-input');
        if (removeInput) removeInput.value = '1';
        const previewImg = document.getElementById('avatar-img-preview');
        const fallback = document.getElementById('avatar-initials-fallback');
        if (previewImg) previewImg.style.display = 'none';
        if (fallback) fallback.style.display = 'block';
        const fileInput = document.getElementById('avatar-input');
        if (fileInput) fileInput.value = '';
    }
}
</script>
@endpush
@endsection
