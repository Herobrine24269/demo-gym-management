@extends('layouts.dashboard')
@section('title', 'Edit PT Session')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">PT</span></div>
        <h1 class="page-title">Edit PT Session</h1>
    </div>
    <a href="{{ route('admin.pt-sessions.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:560px;">
    <form method="POST" action="{{ route('admin.pt-sessions.update', $session) }}">
    @csrf @method('PUT')
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Scheduled Date & Time *</label>
                <input type="datetime-local" name="scheduled_at" class="form-control-dark" value="{{ old('scheduled_at', \Carbon\Carbon::parse($session->scheduled_at)->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div>
                <label class="form-label-dark">Status</label>
                <select name="status" class="form-control-dark form-select-dark">
                    @foreach(['scheduled','completed','cancelled','no_show'] as $s)
                    <option value="{{ $s }}" {{ old('status', $session->status)===$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label-dark">Duration (min)</label><input type="number" name="duration_minutes" class="form-control-dark" value="{{ old('duration_minutes', $session->duration_minutes) }}" min="30"></div>
                <div><label class="form-label-dark">Amount (PKR)</label><input type="number" name="amount" class="form-control-dark" value="{{ old('amount', $session->amount) }}" min="0" step="0.01"></div>
            </div>
            <div><label class="form-label-dark">Notes</label><textarea name="notes" class="form-control-dark form-textarea-dark">{{ old('notes', $session->notes) }}</textarea></div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save</button>
        <a href="{{ route('admin.pt-sessions.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
