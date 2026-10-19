@extends('layouts.dashboard')
@section('title', 'Create Diet Plan')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">NUTRITION</span></div>
        <h1 class="page-title">Create Diet Plan</h1>
    </div>
    <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:580px;">
    <form method="POST" action="{{ route('admin.diet-plans.store') }}">
    @csrf
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div><label class="form-label-dark">Plan Name *</label><input type="text" name="name" class="form-control-dark" value="{{ old('name') }}" required placeholder="e.g. Lean Bulk Protocol"></div>
            <div><label class="form-label-dark">Goal</label><input type="text" name="goal" class="form-control-dark" value="{{ old('goal') }}" placeholder="e.g. Muscle Gain, Fat Loss, Maintenance"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label-dark">Calories (kcal)</label><input type="number" name="total_calories" class="form-control-dark" value="{{ old('total_calories') }}" min="0"></div>
                <div><label class="form-label-dark">Protein (g)</label><input type="number" name="protein_g" class="form-control-dark" value="{{ old('protein_g') }}" min="0"></div>
                <div><label class="form-label-dark">Carbs (g)</label><input type="number" name="carbs_g" class="form-control-dark" value="{{ old('carbs_g') }}" min="0"></div>
                <div><label class="form-label-dark">Fat (g)</label><input type="number" name="fat_g" class="form-control-dark" value="{{ old('fat_g') }}" min="0"></div>
            </div>
            <div><label class="form-label-dark">Description</label><textarea name="description" class="form-control-dark form-textarea-dark" placeholder="Plan overview...">{{ old('description') }}</textarea></div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">add</span> Create Plan</button>
        <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
