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

    <a href="{{ route('trainer.posts.index') }}" class="nav-link-dark {{ request()->routeIs('trainer.posts.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">article</span>
        <span>Posts &amp; Guides</span>
    </a>

    <a href="{{ route('trainer.profile.edit') }}" class="nav-link-dark {{ request()->routeIs('trainer.profile.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">manage_accounts</span>
        <span>My Profile</span>
    </a>
</div>

<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
    <div class="sidebar-section-label">PUBLIC PLATFORM</div>
    <a href="{{ route('posts.index') }}" class="nav-link-dark">
        <span class="material-symbols-outlined">article</span>
        <span>View Public Feed</span>
    </a>
</div>
