@extends('layouts.dashboard')
@section('title', 'Create Workout Plan')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">TRAINER</span></div>
        <h1 class="page-title">Create Workout Plan</h1>
    </div>
    <a href="{{ route('trainer.workout-plans.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:580px;">
    <form method="POST" action="{{ route('trainer.workout-plans.store') }}">
    @csrf
    <div class="card-dark">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label-dark">Member *</label>
                @php $trainer = auth()->user()->trainer; @endphp
                <select name="member_id" class="form-control-dark form-select-dark" required>
                    <option value="">Select member...</option>
                    @foreach(\App\Models\TrainerAssignment::where('trainer_id', $trainer->id)->whereNull('ended_at')->with('member.user')->get() as $assign)
                    <option value="{{ $assign->member->id }}" {{ old('member_id')==$assign->member->id ? 'selected':'' }}>{{ $assign->member->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-dark">Plan Title *</label>
                <input type="text" name="title" class="form-control-dark" value="{{ old('title') }}" required placeholder="e.g. Push-Pull-Legs 12-Week">
            </div>
            <div>
                <label class="form-label-dark">Duration (weeks) *</label>
                <input type="number" name="weeks" class="form-control-dark" value="{{ old('weeks', 8) }}" required min="1" max="52">
            </div>
            <div>
                <label class="form-label-dark">Description / Notes</label>
                <textarea name="description" class="form-control-dark form-textarea-dark" placeholder="Goals, methodology, frequency...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">add</span> Create Plan</button>
        <a href="{{ route('trainer.workout-plans.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
