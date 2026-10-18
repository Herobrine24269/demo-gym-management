@extends('layouts.auth')
@section('title', '403 — Access Denied')
@section('content')
<div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px 16px;text-align:center;">
    <div style="margin-bottom:24px;">
        <span class="material-symbols-outlined" style="font-size:72px;color:var(--color-error);">lock</span>
    </div>
    <div style="font-family:var(--font-heading);font-size:72px;font-weight:700;color:var(--color-surface-highest);line-height:1;">403</div>
    <div style="font-family:var(--font-heading);font-size:24px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin:12px 0 8px;letter-spacing:.04em;">Access Denied</div>
    <p style="font-size:14px;color:var(--color-outline);max-width:360px;margin-bottom:32px;">You do not have permission to view this page. Contact your administrator if you think this is a mistake.</p>
    <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
        <a href="{{ auth()->check() ? auth()->user()->dashboardRoute() : route('home') }}" class="btn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Go to Dashboard
        </a>
        @if(auth()->check())
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn-ghost">Sign Out</button>
        </form>
        @endif
    </div>
</div>
@endsection
