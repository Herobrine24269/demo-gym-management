@extends('layouts.dashboard')
@section('title', 'Add-ons & Shop')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">SHOP</span></div>
        <h1 class="page-title">Add-ons & Shop</h1>
    </div>
    <a href="{{ route('admin.addons.create') }}" class="btn-primary">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> Add Item
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
    @forelse($addons as $addon)
    <div class="card-dark" style="display:flex;flex-direction:column;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
            <div>
                <div style="font-family:var(--font-heading);font-size:15px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $addon->name }}</div>
                <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">{{ $addon->category }}</div>
            </div>
            @if($addon->is_active) <span class="badge-active">Active</span>
            @else <span class="badge-expired">Inactive</span> @endif
        </div>
        @if($addon->description)<p style="font-size:12px;color:var(--color-outline);flex:1;margin:0 0 12px;">{{ Str::limit($addon->description, 60) }}</p>@endif
        <div style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-primary-container);margin-bottom:12px;">PKR {{ number_format($addon->price) }}</div>
        <div style="display:flex;gap:4px;margin-top:auto;">
            <a href="{{ route('admin.addons.edit', $addon) }}" class="btn-ghost btn-sm btn-icon" title="Edit"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></a>
            <form method="POST" action="{{ route('admin.addons.destroy', $addon) }}" onsubmit="return confirm('Delete this addon?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm btn-icon"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
            </form>
        </div>
    </div>
    @empty
    <div class="card-dark" style="grid-column:1/-1;"><div class="empty-state"><span class="material-symbols-outlined">shopping_bag</span><div class="empty-state-title">No Add-ons Yet</div><div class="empty-state-body"><a href="{{ route('admin.addons.create') }}" style="color:var(--color-primary-container);">Add first item →</a></div></div></div>
    @endforelse
</div>
@endsection
