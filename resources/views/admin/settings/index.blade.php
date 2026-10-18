@extends('layouts.dashboard')
@section('title', 'Settings')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">SYSTEM</span></div>
        <h1 class="page-title">Gym Settings</h1>
    </div>
</div>
<div style="max-width:640px;">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="card-dark">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Gym Information</div>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label class="form-label-dark">Gym Name *</label>
                    <input type="text" name="gym_name" class="form-control-dark" value="{{ old('gym_name', $settings['gym_name'] ?? '') }}" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label-dark">Phone</label>
                        <input type="text" name="gym_phone" class="form-control-dark" value="{{ old('gym_phone', $settings['gym_phone'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label-dark">Email</label>
                        <input type="email" name="gym_email" class="form-control-dark" value="{{ old('gym_email', $settings['gym_email'] ?? '') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label-dark">Address</label>
                    <textarea name="gym_address" class="form-control-dark form-textarea-dark">{{ old('gym_address', $settings['gym_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-dark" style="margin-top:16px;">
            <div class="section-title" style="font-size:15px;margin-bottom:16px;">Retention & Alert Settings</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label class="form-label-dark">Inactive Alert After (days)</label>
                    <input type="number" name="inactive_threshold_days" class="form-control-dark" value="{{ old('inactive_threshold_days', $settings['inactive_threshold_days'] ?? 10) }}" min="1" max="60">
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Members absent this many days trigger red-list flag.</div>
                </div>
                <div>
                    <label class="form-label-dark">Renewal Alert Before (days)</label>
                    <input type="number" name="renewal_alert_days" class="form-control-dark" value="{{ old('renewal_alert_days', $settings['renewal_alert_days'] ?? 7) }}" min="1" max="30">
                    <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Alert when membership expires within this window.</div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;">
            <button type="submit" class="btn-primary">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
