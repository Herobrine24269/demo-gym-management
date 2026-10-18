{{-- Member sidebar --}}
<div class="space-y-1">
    <div class="sidebar-section-label">MY PORTAL</div>

    <a href="{{ route('member.dashboard') }}" class="nav-link-dark {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">grid_view</span>
        <span>My Dashboard</span>
    </a>

    <a href="{{ route('member.attendance.index') }}" class="nav-link-dark {{ request()->routeIs('member.attendance.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">calendar_month</span>
        <span>My Attendance</span>
    </a>

    <a href="{{ route('member.profile.edit') }}" class="nav-link-dark {{ request()->routeIs('member.profile.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">person</span>
        <span>My Profile</span>
    </a>

    <a href="{{ route('home') }}" class="nav-link-dark">
        <span class="material-symbols-outlined">home</span>
        <span>Gym Home</span>
    </a>
</div>
