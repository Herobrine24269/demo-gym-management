@extends('layouts.dashboard')
@section('title', 'Edit Member: ' . $member->user->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">Edit Member: {{ $member->user->name }}</h1>
        <p class="page-title-sub">{{ $member->member_code }} · Member since {{ $member->joined_at->format('d M Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to Profile
        </a>
    </div>
</div>

@if($errors->any())
<div class="toast-dark error" style="margin-bottom:20px;">
    <span class="material-symbols-outlined" style="color:var(--color-error);flex-shrink:0;">error</span>
    <div>
        <div style="font-weight:600;margin-bottom:4px;">Please correct the errors below:</div>
        <ul style="margin:0;padding-left:18px;font-size:12px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div style="max-width:760px;">
    <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-dark" style="margin-bottom:20px;">
            <div class="section-title" style="font-size:16px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">person</span>
                Edit Profile
            </div>

            {{-- Profile Photo Sub-section --}}
            <div style="display:flex;align-items:center;gap:20px;padding:16px;background:var(--color-surface-container);border-radius:8px;border:1px solid var(--color-surface-highest);margin-bottom:20px;flex-wrap:wrap;">
                <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;background:var(--color-surface-high);border:2px solid var(--color-primary-container);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($member->user->avatar_url)
                        <img id="admin-member-avatar-preview" src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <img id="admin-member-avatar-preview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                        <span id="admin-member-initials" style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-primary-container);">
                            {{ $member->initials() }}
                        </span>
                    @endif
                </div>

                <div style="flex:1;min-width:240px;">
                    <div style="font-family:var(--font-heading);font-size:13px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:4px;">Profile Picture</div>
                    <div style="font-size:11px;color:var(--color-outline);margin-bottom:10px;">Update member photo for ID badge, attendance check-in, and member roster.</div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <label for="admin-avatar-file" class="btn-primary btn-sm" style="cursor:pointer;margin:0;">
                            <span class="material-symbols-outlined" style="font-size:16px;">upload</span> Choose Photo
                        </label>
                        <input type="file" id="admin-avatar-file" name="avatar" accept="image/*" style="display:none;" onchange="previewAdminMemberAvatar(this)">

                        @if($member->user->avatar)
                        <button type="button" class="btn-ghost btn-sm" onclick="removeAdminMemberAvatar()" style="color:var(--color-error);">
                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Remove
                        </button>
                        <input type="hidden" name="remove_avatar" id="admin-remove-avatar-input" value="0">
                        @endif
                    </div>
                </div>
            </div>

            {{-- Credentials & Personal Details --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <label class="form-label-dark">Full Name <span style="color:var(--color-primary-container);">*</span></label>
                    <input type="text" name="name" class="form-control-dark" value="{{ old('name', $member->user->name) }}" required>
                </div>

                {{-- Email Address: EDITABLE ONLY ON ADMIN DASHBOARD --}}
                <div>
                    <label class="form-label-dark" style="display:flex;align-items:center;justify-content:space-between;">
                        <span>Email Address <span style="color:var(--color-primary-container);">*</span></span>
                        <span style="font-size:10px;color:var(--color-secondary);font-weight:600;text-transform:none;">(Admin Editable)</span>
                    </label>
                    <input type="email" name="email" class="form-control-dark" value="{{ old('email', $member->user->email) }}" required>
                    @error('email')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label-dark">Phone Number</label>
                    <input type="text" name="phone" class="form-control-dark" value="{{ old('phone', $member->user->phone) }}" placeholder="+92-300-1234567">
                </div>

                <div>
                    <label class="form-label-dark">Gender</label>
                    <select name="gender" class="form-control-dark form-select-dark">
                        <option value="">Select Gender...</option>
                        @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $k => $v)
                        <option value="{{ $k }}" {{ old('gender', $member->gender) === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label-dark">Date of Birth</label>
                    <input type="date" name="dob" class="form-control-dark" value="{{ old('dob', $member->dob?->format('Y-m-d')) }}">
                </div>

                <div>
                    <label class="form-label-dark">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control-dark" value="{{ old('emergency_contact', $member->emergency_contact) }}" placeholder="Name & contact phone">
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Residential Address</label>
                    <textarea name="address" class="form-control-dark form-textarea-dark" rows="2">{{ old('address', $member->address) }}</textarea>
                </div>

                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Health Notes / Medical Conditions</label>
                    <textarea name="health_notes" class="form-control-dark form-textarea-dark" rows="2" placeholder="Injuries, medications, or dietary restrictions...">{{ old('health_notes', $member->health_notes) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
            <button type="submit" class="btn-primary" style="padding:12px 24px;">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Changes
            </button>
            <a href="{{ route('admin.members.show', $member) }}" class="btn-ghost">Cancel</a>
        </div>
    </form>

    {{-- Danger Zone Delete Member --}}
    <div style="background:rgba(147,0,10,.12);border:1px solid rgba(255,180,171,.25);border-radius:12px;padding:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="font-family:var(--font-heading);font-size:14px;font-weight:700;color:var(--color-error);text-transform:uppercase;">Danger Zone: Delete Member</div>
            <div style="font-size:12px;color:var(--color-on-surface-variant);margin-top:2px;">
                Permanently deactivates {{ $member->user->name }}'s member record and portal access account.
            </div>
        </div>
        <form method="POST" action="{{ route('admin.members.destroy', $member) }}" onsubmit="return confirm('Are you sure you want to permanently delete member &quot;{{ addslashes($member->user->name) }}&quot; ({{ $member->member_code }})?');" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" style="padding:10px 18px;font-size:12px;">
                <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Delete Member
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewAdminMemberAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('admin-member-avatar-preview');
            const fallback = document.getElementById('admin-member-initials');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            if (fallback) fallback.style.display = 'none';

            const removeInput = document.getElementById('admin-remove-avatar-input');
            if (removeInput) removeInput.value = '0';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeAdminMemberAvatar() {
    if (confirm('Remove profile photo for this member?')) {
        const removeInput = document.getElementById('admin-remove-avatar-input');
        if (removeInput) removeInput.value = '1';
        const previewImg = document.getElementById('admin-member-avatar-preview');
        const fallback = document.getElementById('admin-member-initials');
        if (previewImg) previewImg.style.display = 'none';
        if (fallback) fallback.style.display = 'block';
        const fileInput = document.getElementById('admin-avatar-file');
        if (fileInput) fileInput.value = '';
    }
}
</script>
@endpush
@endsection
