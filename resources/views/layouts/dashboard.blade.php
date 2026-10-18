<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Kinetic Apex</title>
    <link rel="stylesheet" href="{{ asset('css/gym.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    @stack('head')
</head>
<body>

{{-- ── Sidebar overlay (mobile) ───────────────────────────── --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ── Sidebar ─────────────────────────────────────────────── --}}
<aside id="sidebar">
    <div>
        {{-- Header --}}
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <span class="material-symbols-outlined" style="font-size:20px;">bolt</span>
                </div>
                <div>
                    <div class="sidebar-brand-name">KINETIC APEX</div>
                    <div class="sidebar-brand-sub">{{ strtoupper(auth()->user()->role) }} // OS V4</div>
                </div>
            </div>
            <span class="badge-pro">PRO</span>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            @include('components.sidebar.' . auth()->user()->role)
        </nav>
    </div>

    {{-- User footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                <div style="position:relative;flex-shrink:0;">
                    <div class="user-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            <span class="material-symbols-outlined" style="font-size:18px;">person</span>
                        @endif
                    </div>
                    <span class="user-online-dot"></span>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ auth()->user()->name }}</div>
                    <div style="font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--color-outline);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-icon-btn" title="Sign out" style="color:var(--color-on-surface-variant);" onmouseover="this.style.color='var(--color-error)'" onmouseout="this.style.color='var(--color-on-surface-variant)'">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Topbar ──────────────────────────────────────────────── --}}
<header id="topbar">
    <button class="topbar-icon-btn" onclick="toggleSidebar()" title="Toggle sidebar" style="display:flex;">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <div class="topbar-search">
        <span class="material-symbols-outlined search-icon">search</span>
        <input type="text" id="global-search" placeholder="Search member, payment, trainer..." autocomplete="off">
    </div>

    <div class="topbar-actions">
        {{-- Alert count (admin only) --}}
        @if(auth()->user()->isAdmin())
            @php $alertCount = \App\Models\InactiveCase::open()->count(); @endphp
            @if($alertCount > 0)
            <a href="{{ route('admin.inactive.index') }}" style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:4px;background:rgba(147,0,10,.2);border:1px solid rgba(255,180,171,.3);color:var(--color-error);text-decoration:none;font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;font-weight:600;text-transform:uppercase;" class="d-none d-lg-flex">
                <span style="width:8px;height:8px;border-radius:50%;background:var(--color-error);animation:pulseGreen 2s infinite;"></span>
                {{ $alertCount }} Flags
            </a>
            @endif
        @endif

        <button class="topbar-icon-btn" title="Notifications" style="position:relative;">
            <span class="material-symbols-outlined">notifications</span>
            <span class="notif-dot"></span>
        </button>

        <div style="width:1px;height:24px;background:var(--color-surface-highest);"></div>

        <div class="user-avatar" style="width:32px;height:32px;cursor:pointer;" title="{{ auth()->user()->name }}">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                <span class="material-symbols-outlined" style="font-size:16px;">person</span>
            @endif
        </div>
    </div>
</header>

{{-- ── Main content ─────────────────────────────────────────── --}}
<div id="main-content">
    <div class="page-body">

        {{-- Toast notifications --}}
        @if(session('success'))
            <div class="toast-container-dark">
                <div class="toast-dark success" id="toast-ok">
                    <span class="material-symbols-outlined" style="color:var(--color-primary-container);flex-shrink:0;">check_circle</span>
                    <span style="font-size:13px;">{{ session('success') }}</span>
                    <button onclick="document.getElementById('toast-ok').remove()" style="margin-left:auto;background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:18px;">×</button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-container-dark">
                <div class="toast-dark error" id="toast-err">
                    <span class="material-symbols-outlined" style="color:var(--color-error);flex-shrink:0;">error</span>
                    <span style="font-size:13px;">{{ session('error') }}</span>
                    <button onclick="document.getElementById('toast-err').remove()" style="margin-left:auto;background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:18px;">×</button>
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    /* ── Sidebar toggle ── */
    function toggleSidebar() {
        const sb  = document.getElementById('sidebar');
        const tb  = document.getElementById('topbar');
        const mc  = document.getElementById('main-content');
        const ov  = document.getElementById('sidebar-overlay');
        const isMobile = window.innerWidth < 992;

        if (isMobile) {
            sb.classList.toggle('open');
            ov.classList.toggle('show');
        } else {
            sb.classList.toggle('collapsed');
            tb.classList.toggle('full-width');
            mc.classList.toggle('full-width');
        }
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('show');
    }

    /* ── Auto-dismiss toasts ── */
    setTimeout(() => {
        document.querySelectorAll('.toast-dark').forEach(t => { t.style.opacity = '0'; t.style.transition = 'opacity .4s'; setTimeout(() => t.remove(), 400); });
    }, 4000);

    /* ── Active nav link highlight (fallback — Blade handles most via routeIs) ── */
    document.querySelectorAll('.nav-link-dark[href]').forEach(link => {
        if (!link.classList.contains('active') && link.href && window.location.href.startsWith(link.href) && link.href !== window.location.origin + '/') {
            link.classList.add('active');
        }
    });
</script>
@stack('scripts')
</body>
</html>
