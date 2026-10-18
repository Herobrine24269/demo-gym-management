@extends('layouts.dashboard')
@section('title', 'Edit Workout Plan')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER</span></div>
        <h1 class="page-title">Edit Plan</h1>
    </div>
    <a href="{{ route('trainer.workout-plans.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:560px;">
    <form method="POST" action="{{ route('trainer.workout-plans.update', $workoutPlan) }}">
    @csrf @method('PUT')
    <div class="card-dark">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Plan Title *</label>
                <input type="text" name="title" class="form-control-dark" value="{{ old('title', $workoutPlan->title) }}" required>
            </div>
            <div>
                <label class="form-label-dark">Status</label>
                <select name="status" class="form-control-dark form-select-dark" required>
                    @foreach(['active','completed','paused'] as $s)
                    <option value="{{ $s }}" {{ old('status', $workoutPlan->status)===$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Description / Notes</label>
                <textarea name="description" class="form-control-dark form-textarea-dark">{{ old('description', $workoutPlan->description) }}</textarea>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save</button>
        <a href="{{ route('trainer.workout-plans.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
