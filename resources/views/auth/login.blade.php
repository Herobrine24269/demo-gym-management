@extends('layouts.auth')

@section('title', 'Member & Staff Login')

@section('content')
<div style="min-height:100vh;display:grid;grid-template-columns:1fr;place-items:center;padding:32px 16px;">
<div style="width:100%;max-width:1100px;display:grid;grid-template-columns:1fr 1fr;gap:24px;min-height:700px;" class="auth-grid">

    {{-- ── Left: Brand panel ─────────────────────────────── --}}
    <div style="position:relative;display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;border-radius:12px;background:var(--color-surface-lowest);padding:32px;box-shadow:0 24px 64px rgba(0,0,0,.5);">
        {{-- Glow blobs --}}
        <div style="position:absolute;top:-80px;left:-80px;width:320px;height:320px;border-radius:50%;background:rgba(57,255,20,.06);filter:blur(80px);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-60px;right:-60px;width:260px;height:260px;border-radius:50%;background:rgba(0,169,232,.07);filter:blur(80px);pointer-events:none;"></div>

        {{-- Top brandmark --}}
        <div style="position:relative;z-index:1;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:40px;height:40px;border-radius:4px;background:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:20px;font-weight:700;color:var(--color-on-primary);">K</div>
                    <div>
                        <div style="font-family:var(--font-heading);font-size:16px;letter-spacing:.08em;font-weight:600;text-transform:uppercase;color:var(--color-primary);">KINETIC APEX</div>
                        <div style="font-family:var(--font-heading);font-size:10px;letter-spacing:.1em;color:var(--color-outline);text-transform:uppercase;">PERFORMANCE FACILITY • OS V4.2</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:var(--color-surface-high);font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;color:var(--color-primary-container);text-transform:uppercase;">
                    <span class="pulse-green" style="width:7px;height:7px;"></span> SYSTEM OPTIMAL
                </div>
            </div>

            {{-- Hero image placeholder --}}
            <div style="width:100%;height:180px;border-radius:8px;overflow:hidden;background:var(--color-surface-container);position:relative;margin-bottom:24px;">
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1c1b1b 0%,#0e0e0e 100%);display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-outlined" style="font-size:64px;color:var(--color-surface-highest);">fitness_center</span>
                </div>
                <div style="position:absolute;inset:0;background:linear-gradient(to top, var(--color-surface-lowest) 0%, transparent 60%);"></div>
                <div style="position:absolute;bottom:12px;left:12px;">
                    <span style="font-family:var(--font-heading);font-size:20px;font-weight:700;text-transform:uppercase;color:var(--color-primary-container);">PRECISION IRON & DATA</span>
                </div>
            </div>

            {{-- Feature list --}}
            <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.1em;color:var(--color-outline);text-transform:uppercase;margin-bottom:10px;">UNIFIED APEX PROTOCOLS</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach([
                    ['how_to_reg','Manual Attendance Logging','Desk-side rapid search & one-tap check-in with zero hardware dependency.'],
                    ['restaurant_menu','Diet Plan Library','Targeted macronutrient matrices and trainer-prescribed calories.'],
                    ['monitoring','Automated Retention Alerts','Real-time inactivity flags for members absent past 10 days.'],
                    ['sports_gymnastics','Trainer Client Roster','Live periodization tracking, PR logs and biometric assessments.'],
                ] as [$icon,$title,$desc])
                <div style="display:flex;align-items:flex-start;gap:10px;padding:10px;border-radius:8px;background:rgba(28,27,27,.7);">
                    <div style="padding:4px;border-radius:4px;background:var(--color-surface-high);color:var(--color-primary-container);flex-shrink:0;">
                        <span class="material-symbols-outlined" style="font-size:16px;">{{ $icon }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-heading);font-size:13px;letter-spacing:.05em;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);">{{ $title }}</div>
                        <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom status --}}
        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;padding-top:16px;">
            <div style="display:flex;align-items:center;gap:6px;font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">
                <span class="material-symbols-outlined" style="font-size:14px;color:var(--color-primary-container);">encrypted</span>
                ZERO SCANNER ARCHITECTURE • MANUAL OPS
            </div>
            <span style="font-family:var(--font-heading);font-size:10px;letter-spacing:.1em;color:var(--color-primary-container);text-transform:uppercase;">SECURE SSL-256</span>
        </div>
    </div>

    {{-- ── Right: Auth form ───────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;">
        <div style="width:100%;max-width:440px;background:var(--color-surface-container);border-radius:12px;padding:32px;border:1px solid var(--color-surface-highest);box-shadow:0 24px 64px rgba(0,0,0,.4);">

            {{-- ── BACK BUTTON ────────────────────────────────────── --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <a href="{{ route('home') }}" class="btn-ghost" style="padding:7px 14px;font-size:12px;border:1px solid var(--color-surface-highest);background:var(--color-surface-low);display:inline-flex;align-items:center;gap:6px;border-radius:6px;text-decoration:none;color:var(--color-on-surface);transition:all .2s;" onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.color='var(--color-primary-container)'" onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.color='var(--color-on-surface)'">
                    <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                    <span>Back to Website</span>
                </a>
                <span style="font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">PORTAL GATEWAY</span>
            </div>

            {{-- Form header --}}
            <div style="margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span style="padding:2px 8px;border-radius:3px;background:var(--color-primary-container);color:var(--color-on-primary);font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;font-weight:600;text-transform:uppercase;">ACCESS TERMINAL</span>
                    <span style="font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">SECURE DESK TERMINAL</span>
                </div>
                <h1 style="font-family:var(--font-heading);font-size:28px;font-weight:700;text-transform:uppercase;color:var(--color-primary);margin:0 0 6px;">MEMBER & STAFF ACCESS</h1>
                <p style="font-size:13px;color:var(--color-on-surface-variant);margin:0;">Sign in to access your workout schedules, assigned diet plans, or facility dashboard.</p>
            </div>

            {{-- Info banner --}}
            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px;border-radius:8px;background:rgba(0,169,232,.1);margin-bottom:20px;">
                <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-secondary);flex-shrink:0;margin-top:2px;" data-variant="FILL:1">info</span>
                <div>
                    <div style="font-size:12px;font-weight:600;color:var(--color-secondary-container);">System Auto-Detects Privileges Upon Sign In</div>
                    <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">Members route to Personal Portal; Staff routes to Attendance Desk; Admins route to Executive Dashboard.</div>
                </div>
            </div>

            {{-- Error --}}
            @if($errors->any())
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;padding:12px;border-radius:8px;background:var(--color-error-container);margin-bottom:20px;">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-on-error-container);flex-shrink:0;">error</span>
                    <div style="font-size:12px;color:var(--color-on-error-container);">{{ $errors->first() }}</div>
                </div>
            </div>
            @endif

            {{-- Login form --}}
            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <div style="margin-bottom:16px;">
                    <label class="form-label-dark" for="email">Email Address</label>
                    <input
                        class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="your@email.com"
                        autocomplete="email"
                        required
                    >
                </div>

                <div style="margin-bottom:8px;">
                    <label class="form-label-dark" for="password">Password</label>
                    <div style="position:relative;">
                        <input
                            class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            style="padding-right:44px;"
                        >
                        <button type="button" onclick="togglePw()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--color-outline);cursor:pointer;">
                            <span class="material-symbols-outlined" id="pw-eye" style="font-size:18px;">visibility</span>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:var(--color-on-surface-variant);">
                        <input type="checkbox" name="remember" style="accent-color:var(--color-primary-container);">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:13px;">
                    <span class="material-symbols-outlined" style="font-size:18px;">login</span>
                    SIGN IN TO SYSTEM
                </button>
            </form>

            <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--color-surface-highest);text-align:center;">
                <p style="font-size:11px;color:var(--color-outline);">Contact admin if you cannot access your account.</p>
                <a href="{{ route('home') }}" style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-primary-container);text-decoration:none;text-transform:uppercase;">← Back to Gym Home</a>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
function togglePw() {
    const pw  = document.getElementById('password');
    const eye = document.getElementById('pw-eye');
    if (pw.type === 'password') { pw.type = 'text'; eye.textContent = 'visibility_off'; }
    else { pw.type = 'password'; eye.textContent = 'visibility'; }
}
</script>
<style>
@media (max-width: 700px) {
    .auth-grid { grid-template-columns: 1fr !important; }
    .auth-grid > div:first-child { display: none !important; }
}
</style>
@endpush
@endsection
