{{-- Trainer sidebar --}}
<div class="space-y-1">
    <div class="sidebar-section-label">MY WORKSPACE</div>

    <a href="{{ route('trainer.dashboard') }}" class="nav-link-dark {{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">grid_view</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('trainer.members.index') }}" class="nav-link-dark {{ request()->routeIs('trainer.members.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">group</span>
        <span>My Members</span>
    </a>

    <a href="{{ route('trainer.workout-plans.index') }}" class="nav-link-dark {{ request()->routeIs('trainer.workout-plans.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">fitness_center</span>
        <span>Workout Plans</span>
    </a>
</div>
