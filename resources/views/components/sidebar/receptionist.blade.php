{{-- Receptionist sidebar --}}
<div class="space-y-1">
    <div class="sidebar-section-label">DESK MENU</div>

    <a href="{{ route('receptionist.dashboard') }}" class="nav-link-dark {{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">grid_view</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('receptionist.attendance.index') }}" class="nav-link-dark {{ request()->routeIs('receptionist.attendance.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">how_to_reg</span>
        <span>Mark Attendance</span>
        <span style="margin-left:auto;display:flex;align-items:center;gap:4px;font-family:var(--font-heading);font-size:10px;letter-spacing:.06em;color:var(--color-primary-container);">
            <span class="pulse-green" style="width:6px;height:6px;"></span>LIVE
        </span>
    </a>

    <a href="{{ route('admin.inactive.index') }}" class="nav-link-dark {{ request()->routeIs('admin.inactive.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined" style="color:var(--color-error);">crisis_alert</span>
        <span>Inactive Members</span>
    </a>

    <a href="{{ route('admin.payments.index') }}" class="nav-link-dark {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">receipt_long</span>
        <span>Payments</span>
    </a>

    @if((string) \App\Models\GymSetting::get('receptionist_can_view_payment_requests', '0') === '1')
        <a href="{{ route('admin.payment-requests.index') }}" class="nav-link-dark {{ request()->routeIs('admin.payment-requests.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">pending_actions</span>
            <span>Payment Requests</span>
            @php $recPending = \App\Models\PaymentSubmission::pending()->count(); @endphp
            @if($recPending > 0)
                <span class="badge-accent" style="margin-left:auto;background:var(--color-primary);color:#000;font-size:10px;font-weight:800;padding:2px 7px;border-radius:10px;">
                    {{ $recPending }}
                </span>
            @endif
        </a>
    @endif

    <a href="{{ route('admin.renewals.index') }}" class="nav-link-dark {{ request()->routeIs('admin.renewals.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">autorenew</span>
        <span>Renewals</span>
    </a>

    <a href="{{ route('admin.members.index') }}" class="nav-link-dark {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">group</span>
        <span>Member List</span>
    </a>
</div>
