@extends('layouts.dashboard')
@section('title', 'Admin Profile — System Administration')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">ADMINISTRATOR</span></div>
        <h1 class="page-title">Edit Admin Profile</h1>
        <p class="page-title-sub">Manage your master system account, credentials, and profile picture</p>
    </div>
</div>

<div style="max-width:680px;">
    <div class="card-dark">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── PROFILE PHOTO SECTION ────────────────────────── --}}
            <div style="padding-bottom:24px;margin-bottom:24px;border-bottom:1px solid var(--color-surface-highest);">
                <div class="section-title" style="font-size:15px;margin-bottom:6px;">Profile Picture</div>
                <p style="font-size:12px;color:var(--color-outline);margin:0 0 16px;">Upload a professional avatar for your administrator profile and dashboard header.</p>

                <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                    {{-- Avatar Preview Box --}}
                    <div style="position:relative;">
                        <div id="admin-avatar-preview-box" style="width:84px;height:84px;border-radius:50%;overflow:hidden;background:var(--color-surface-container);border:2px solid var(--color-primary-container);box-shadow:var(--glow-green);display:flex;align-items:center;justify-content:center;">
                            @if($user->avatar_url)
                                <img id="admin-avatar-img-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <img id="admin-avatar-img-preview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                                <span id="admin-avatar-initials-fallback" style="font-family:var(--font-heading);font-size:32px;font-weight:700;color:var(--color-primary-container);">
                                    {{ $user->initials() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Controls --}}
                    <div style="flex:1;min-width:220px;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px;">
                            <label for="admin-avatar-input" class="btn-primary btn-sm" style="cursor:pointer;margin:0;">
                                <span class="material-symbols-outlined" style="font-size:16px;">upload</span> Upload New Photo
                            </label>
                            <input type="file" id="admin-avatar-input" name="avatar" accept="image/png,image/jpeg,image/webp" style="display:none;" onchange="previewAdminProfileAvatar(this)">

                            @if($user->avatar)
                            <button type="button" class="btn-ghost btn-sm" onclick="markRemoveAdminProfileAvatar()" id="remove-admin-avatar-btn" style="color:var(--color-error);">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Remove Photo
                            </button>
                            <input type="hidden" name="remove_avatar" id="remove-admin-avatar-input" value="0">
                            @endif
                        </div>
                        <div style="font-size:11px;color:var(--color-outline);">
                            PNG, JPG, or WebP up to 4MB. High resolution square image recommended.
                        </div>
                        @error('avatar')
                            <div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── CONTACT & CREDENTIALS ────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:24px;">
                <div class="section-title" style="font-size:15px;margin-bottom:2px;">Account Credentials</div>

                <div>
                    <label class="form-label-dark">Administrator Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                {{-- Editable Email Address on Admin Dashboard --}}
                <div>
                    <label class="form-label-dark" style="display:flex;align-items:center;justify-content:space-between;">
                        <span>Email Address <span style="color:var(--color-primary-container);">*</span></span>
                        <span style="font-size:10px;color:var(--color-primary-container);font-weight:600;letter-spacing:.05em;text-transform:uppercase;">Editable on Admin Dashboard</span>
                    </label>
                    <input type="email" name="email" class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email', $user->email) }}" required>
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">
                        This email is your master administrator login credential.
                    </div>
                    @error('email')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
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
                    <div class="section-title" style="font-size:15px;margin-bottom:2px;">Security & Password</div>
                    <p style="font-size:12px;color:var(--color-outline);margin:0;">Leave blank if you do not want to change your administrator password.</p>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label-dark">New Password</label>
                        <input type="password" name="password" class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Min. 8 characters" autocomplete="new-password">
                        @error('password')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label-dark">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control-dark" placeholder="Re-type new password" autocomplete="new-password">
                    </div>
                </div>
            </div>

            {{-- ── ACTION BUTTONS ───────────────────────────────── --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:16px;border-top:1px solid var(--color-surface-highest);">
                <div style="font-size:12px;color:var(--color-outline);">
                    Role: <span style="color:var(--color-primary-container);font-weight:600;text-transform:uppercase;">Master Administrator</span>
                </div>
                <button type="submit" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAdminProfileAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('admin-avatar-img-preview');
            const fallback = document.getElementById('admin-avatar-initials-fallback');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            if (fallback) {
                fallback.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
}

function markRemoveAdminProfileAvatar() {
    const input = document.getElementById('remove-admin-avatar-input');
    if (input) {
        input.value = '1';
    }
    const preview = document.getElementById('admin-avatar-img-preview');
    if (preview) {
        preview.style.display = 'none';
        preview.src = '';
    }
    const fallback = document.getElementById('admin-avatar-initials-fallback');
    if (fallback) {
        fallback.style.display = 'block';
    }
    const btn = document.getElementById('remove-admin-avatar-btn');
    if (btn) {
        btn.style.opacity = '0.5';
        btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">done</span> Marked for Removal';
    }
}
</script>
@endpush
@endsection
