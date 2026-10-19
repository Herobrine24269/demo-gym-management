@extends('layouts.dashboard')
@section('title', 'Book PT Session')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">PT</span></div>
        <h1 class="page-title">Book PT Session</h1>
    </div>
    <a href="{{ route('admin.pt-sessions.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:560px;">
    <form method="POST" action="{{ route('admin.pt-sessions.store') }}">
    @csrf
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Member *</label>
                <select name="member_id" class="form-control-dark form-select-dark" required>
                    <option value="">Select member...</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id')==$member->id ? 'selected':'' }}>{{ $member->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Trainer *</label>
                <select name="trainer_id" class="form-control-dark form-select-dark" required>
                    <option value="">Select trainer...</option>
                    @foreach($trainers as $trainer)
                    <option value="{{ $trainer->id }}" {{ old('trainer_id')==$trainer->id ? 'selected':'' }}>{{ $trainer->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Scheduled Date & Time *</label>
                <input type="datetime-local" name="scheduled_at" class="form-control-dark" value="{{ old('scheduled_at') }}" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label-dark">Duration (minutes)</label><input type="number" name="duration_minutes" class="form-control-dark" value="{{ old('duration_minutes', 60) }}" min="30" max="180"></div>
                <div><label class="form-label-dark">Amount (PKR)</label><input type="number" name="amount" class="form-control-dark" value="{{ old('amount') }}" min="0" step="0.01"></div>
            </div>
            <div>
                <label class="form-label-dark">Notes</label>
                <textarea name="notes" class="form-control-dark form-textarea-dark" placeholder="Session focus, goals...">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">add</span> Book Session</button>
        <a href="{{ route('admin.pt-sessions.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
