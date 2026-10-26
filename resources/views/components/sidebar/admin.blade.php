{{-- Admin sidebar --}}
<div class="space-y-1">
    <div class="sidebar-section-label">MAIN MENU</div>

    <a href="{{ route('admin.dashboard') }}" class="nav-link-dark {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">grid_view</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.members.index') }}" class="nav-link-dark {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">group</span>
        <span>Members</span>
    </a>

    <a href="{{ route('receptionist.attendance.index') }}" class="nav-link-dark {{ request()->routeIs('receptionist.attendance.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">badge</span>
        <span>Attendance</span>
    </a>

    <a href="{{ route('admin.inactive.index') }}" class="nav-link-dark {{ request()->routeIs('admin.inactive.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined" style="color:var(--color-error);">crisis_alert</span>
        <span>Members Who Stopped Coming</span>
        @php $flagCount = \App\Models\InactiveCase::open()->count(); @endphp
        @if($flagCount > 0)
            <span class="badge-nav" style="margin-left:auto;"></span>
        @endif
    </a>

    <a href="{{ route('admin.payments.index') }}" class="nav-link-dark {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">receipt_long</span>
        <span>Payments</span>
    </a>

    <a href="{{ route('admin.payment-methods.index') }}" class="nav-link-dark {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">account_balance_wallet</span>
        <span>Payment Methods</span>
    </a>

    <a href="{{ route('admin.payment-requests.index') }}" class="nav-link-dark {{ request()->routeIs('admin.payment-requests.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">pending_actions</span>
        <span>Payment Requests</span>
        @php $pendingReqs = \App\Models\PaymentSubmission::pending()->count(); @endphp
        @if($pendingReqs > 0)
            <span class="badge-accent" style="margin-left:auto;background:var(--color-primary);color:#000;font-size:10px;font-weight:800;padding:2px 7px;border-radius:10px;">
                {{ $pendingReqs }}
            </span>
        @endif
    </a>

    <a href="{{ route('admin.renewals.index') }}" class="nav-link-dark {{ request()->routeIs('admin.renewals.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">autorenew</span>
        <span>Renewals</span>
    </a>
</div>

<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
    <div class="sidebar-section-label">MORE</div>

    <a href="{{ route('admin.trainers.index') }}" class="nav-link-dark {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">sports</span>
        <span>Trainers & Staff</span>
    </a>

    <a href="{{ route('admin.pt-sessions.index') }}" class="nav-link-dark {{ request()->routeIs('admin.pt-sessions.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">fitness_center</span>
        <span>Personal Training</span>
    </a>

    <a href="{{ route('admin.addons.index') }}" class="nav-link-dark {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span>Shop & Add-ons</span>
    </a>

    <a href="{{ route('admin.posts.index') }}" class="nav-link-dark {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">article</span>
        <span>Posts</span>
    </a>

    <a href="{{ route('admin.diet-plans.index') }}" class="nav-link-dark {{ request()->routeIs('admin.diet-plans.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">restaurant</span>
        <span>Diet Plans</span>
    </a>

    <a href="{{ route('admin.expenses.index') }}" class="nav-link-dark {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">account_balance</span>
        <span>Expenses</span>
    </a>

    <a href="{{ route('admin.reports.index') }}" class="nav-link-dark {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">analytics</span>
        <span>Reports</span>
    </a>

    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link-dark {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">security</span>
        <span>Activity History</span>
    </a>

    <a href="{{ route('admin.settings.index') }}" class="nav-link-dark {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">settings</span>
        <span>Settings</span>
    </a>

    <a href="{{ route('admin.profile.edit') }}" class="nav-link-dark {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">account_circle</span>
        <span>My Profile</span>
    </a>
</div>

<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
    <div class="sidebar-section-label">PUBLIC PLATFORM</div>
    <a href="{{ route('posts.index') }}" class="nav-link-dark">
        <span class="material-symbols-outlined">article</span>
        <span>Visitor Posts Feed</span>
    </a>
    <a href="{{ route('home') }}" class="nav-link-dark">
        <span class="material-symbols-outlined">public</span>
        <span>Gym Homepage</span>
    </a>
</div>
