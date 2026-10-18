@extends('layouts.dashboard')
@section('title', 'Add Member')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">MEMBERS</span></div>
        <h1 class="page-title">Add New Member</h1>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn-ghost">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.members.store') }}">
@csrf
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">

    {{-- Left: Personal Details --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Personal Information</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Full Name *</label>
                    <input type="text" name="name" class="form-control-dark {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required placeholder="Ahmed Khan">
                    @error('name')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-dark">Email *</label>
                    <input type="email" name="email" class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required placeholder="ahmed@email.com">
                    @error('email')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-dark">Phone</label>
                    <input type="text" name="phone" class="form-control-dark" value="{{ old('phone') }}" placeholder="+92-300-0000000">
                </div>
                <div>
                    <label class="form-label-dark">Password *</label>
                    <input type="password" name="password" class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}" required placeholder="Min. 6 characters">
                    @error('password')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-dark">Gender</label>
                    <select name="gender" class="form-control-dark form-select-dark">
                        <option value="">Select...</option>
                        <option value="male"   {{ old('gender')=='male'   ? 'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>Female</option>
                        <option value="other"  {{ old('gender')=='other'  ? 'selected':'' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="form-label-dark">Date of Birth</label>
                    <input type="date" name="dob" class="form-control-dark" value="{{ old('dob') }}">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Address</label>
                    <textarea name="address" class="form-control-dark form-textarea-dark" placeholder="Home address...">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label class="form-label-dark">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control-dark" value="{{ old('emergency_contact') }}" placeholder="Name or phone">
                </div>
                <div>
                    <label class="form-label-dark">Joined Date *</label>
                    <input type="date" name="joined_at" class="form-control-dark {{ $errors->has('joined_at') ? 'is-invalid' : '' }}" value="{{ old('joined_at', today()->toDateString()) }}" required>
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label-dark">Health Notes</label>
                    <textarea name="health_notes" class="form-control-dark form-textarea-dark" placeholder="Any medical conditions, injuries...">{{ old('health_notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Plan + Trainer --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Membership Plan *</div>
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                @foreach($plans as $plan)
                <label style="display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:6px;background:var(--color-surface-container);border:2px solid {{ old('plan_id')==$plan->id ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};cursor:pointer;" onclick="selectPlan({{ $plan->id }}, {{ $plan->price }})">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id')==$plan->id ? 'checked':'' }} required style="accent-color:var(--color-primary-container);">
                        <div>
                            <div style="font-family:var(--font-heading);font-size:13px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $plan->name }}</div>
                            <div style="font-size:11px;color:var(--color-outline);">{{ $plan->duration_days }} days</div>
                        </div>
                    </div>
                    <div style="font-family:var(--font-heading);font-size:15px;font-weight:700;color:var(--color-primary-container);">PKR {{ number_format($plan->price) }}</div>
                </label>
                @endforeach
            </div>
            @error('plan_id')<div class="invalid-feedback-dark">{{ $message }}</div>@enderror
        </div>

        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:12px;">Payment</div>
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Amount Paid (PKR) *</label>
                <input type="number" name="amount_paid" id="amount_paid" class="form-control-dark {{ $errors->has('amount_paid') ? 'is-invalid' : '' }}" value="{{ old('amount_paid') }}" required min="0" step="0.01">
            </div>
            <div>
                <label class="form-label-dark">Payment Method *</label>
                <select name="payment_method" class="form-control-dark form-select-dark" required>
                    <option value="cash"          {{ old('payment_method','cash')=='cash'          ? 'selected':'' }}>Cash</option>
                    <option value="card"          {{ old('payment_method')=='card'          ? 'selected':'' }}>Card</option>
                    <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
                </select>
            </div>
        </div>

        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:12px;">Assign Trainer (Optional)</div>
            <select name="trainer_id" class="form-control-dark form-select-dark">
                <option value="">No trainer assigned</option>
                @foreach($trainers as $trainer)
                <option value="{{ $trainer->id }}" {{ old('trainer_id')==$trainer->id ? 'selected':'' }}>{{ $trainer->user->name }} — {{ $trainer->specialization }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
            <span class="material-symbols-outlined" style="font-size:20px;">person_add</span>
            Create Member Account
        </button>
    </div>

</div>
</form>

@push('scripts')
<script>
function selectPlan(id, price) {
    document.querySelectorAll('label[onclick]').forEach(l => l.style.borderColor = 'var(--color-surface-highest)');
    event.currentTarget.style.borderColor = 'var(--color-primary-container)';
    document.getElementById('amount_paid').value = price;
}
</script>
@endpush
@endsection
