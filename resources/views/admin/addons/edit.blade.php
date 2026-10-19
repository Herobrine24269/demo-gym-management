@extends('layouts.dashboard')
@section('title', 'Edit: '.$addon->name)

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">SHOP</span></div>
        <h1 class="page-title">Edit: {{ $addon->name }}</h1>
    </div>
    <a href="{{ route('admin.addons.index') }}" class="btn-ghost">← Back</a>
</div>
<div style="max-width:540px;">
    <form method="POST" action="{{ route('admin.addons.update', $addon) }}">
    @csrf @method('PUT')
    <div class="card-dark" style="margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div><label class="form-label-dark">Name *</label><input type="text" name="name" class="form-control-dark" value="{{ old('name', $addon->name) }}" required></div>
            <div><label class="form-label-dark">Category</label><input type="text" name="category" class="form-control-dark" value="{{ old('category', $addon->category) }}"></div>
            <div><label class="form-label-dark">Price (PKR) *</label><input type="number" name="price" class="form-control-dark" value="{{ old('price', $addon->price) }}" required min="0" step="0.01"></div>
            <div><label class="form-label-dark">Description</label><textarea name="description" class="form-control-dark form-textarea-dark">{{ old('description', $addon->description) }}</textarea></div>
            <div style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:6px;background:var(--color-surface-container);">
                <input type="checkbox" name="is_active" value="1" {{ $addon->is_active ? 'checked' : '' }} id="active" style="accent-color:var(--color-primary-container);">
                <label for="active" style="font-size:13px;color:var(--color-on-surface);cursor:pointer;">Available for purchase</label>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn-primary"><span class="material-symbols-outlined" style="font-size:18px;">save</span> Save</button>
        <a href="{{ route('admin.addons.index') }}" class="btn-ghost">Cancel</a>
    </div>
    </form>
</div>
@endsection
