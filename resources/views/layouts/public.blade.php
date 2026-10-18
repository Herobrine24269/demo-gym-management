<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kinetic Apex')</title>
    <meta name="description" content="@yield('meta_description', 'Kinetic Apex — Premier Athletic Facility')">
    <link rel="stylesheet" href="{{ asset('css/gym.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    @stack('head')
</head>
<body>

    {{-- ── Public navbar ──────────────────────────────────────────── --}}
    <nav class="public-nav">
        <div class="public-nav-inner">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:4px;background:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-primary);">K</div>
                <div>
                    <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.08em;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">KINETIC <span style="color:var(--color-primary-container);">APEX</span></div>
                    <div style="font-family:var(--font-heading);font-size:10px;letter-spacing:.1em;color:var(--color-outline);text-transform:uppercase;">Athletic Infrastructure</div>
                </div>
            </div>

            <nav style="display:flex;align-items:center;gap:4px;" class="d-none d-xl-flex">
                <a href="{{ route('home') }}" class="public-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="#posts" class="public-nav-link">Posts & Guides</a>
                <a href="#diet-plans" class="public-nav-link">Diet Plans</a>
                <a href="#plans" class="public-nav-link">Membership Plans</a>
                <a href="#trainers" class="public-nav-link">Trainers</a>
            </nav>

            <div style="display:flex;align-items:center;gap:10px;">
                @auth
                    {{-- Logged-in: show which role + dashboard link + logout --}}
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-primary" style="padding:8px 20px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">dashboard</span>
                        My Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-ghost" style="padding:8px 16px;">
                            <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                            Logout
                        </button>
                    </form>
                @else
                    {{-- Guest: show login --}}
                    <a href="{{ route('login') }}" class="btn-primary" style="padding:8px 20px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">login</span>
                        Member Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main style="padding-top:72px;">
        @yield('content')
    </main>

    {{-- ── Footer ──────────────────────────────────────────────────── --}}
    <footer style="background:var(--color-surface-lowest);border-top:1px solid var(--color-surface-highest);padding:40px 0 24px;">
        <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                <div>
                    <div style="font-family:var(--font-heading);font-size:14px;letter-spacing:.08em;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">KINETIC <span style="color:var(--color-primary-container);">APEX</span></div>
                    <div style="font-size:12px;color:var(--color-outline);margin-top:4px;">Precision Iron & Data — Athletic Infrastructure</div>
                </div>
                <div style="font-size:12px;color:var(--color-outline);">© {{ date('Y') }} Kinetic Apex. All rights reserved.</div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
