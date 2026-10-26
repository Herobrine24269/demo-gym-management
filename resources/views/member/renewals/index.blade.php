@extends('layouts.dashboard')
@section('title', 'Renew Membership')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">MEMBERSHIP RENEWAL</span>
        </div>
        <h1 class="page-title">Renew Your Membership</h1>
        <p class="page-title-sub">Choose a training plan, send your fee via Easypaisa, JazzCash, or Bank Transfer, and submit your receipt for manual verification.</p>
    </div>

    @if($activeMembership)
    <div style="text-align:right;">
        <div class="badge-active" style="font-size:12px;padding:4px 12px;">Current: {{ $activeMembership->plan->name }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">
            Expires {{ $activeMembership->end_date->format('d M Y') }} · {{ $activeMembership->daysRemaining() }} days left
        </div>
    </div>
    @endif
</div>

{{-- Pending Submission Notice --}}
@if($pendingSubmission)
<div style="background:rgba(235,178,0,.1);border:1px solid rgba(235,178,0,.3);border-radius:12px;padding:20px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <span class="material-symbols-outlined" style="font-size:28px;color:#facc15;">hourglass_top</span>
        <div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;color:var(--color-on-surface);text-transform:uppercase;">
                Payment Under Review
            </div>
            <div style="font-size:13px;color:var(--color-on-surface-variant);margin-top:2px;">
                You already submitted a payment of <strong>PKR {{ number_format($pendingSubmission->amount) }}</strong> for <strong>{{ $pendingSubmission->plan->name }}</strong> (Ref: <code style="color:var(--color-primary-container);">{{ $pendingSubmission->transaction_reference }}</code>). Staff will check and approve it soon.
            </div>
        </div>
    </div>
    <a href="{{ route('member.payments.index') }}" class="btn-ghost" style="padding:10px 20px;font-size:12px;">
        View My Payments <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
    </a>
</div>
@endif

{{-- ── STEP 1: CHOOSE A PLAN ─────────────────────────────────── --}}
<div style="margin-bottom:36px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
        <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">/// STEP 1</span>
        <h2 style="font-family:var(--font-heading);font-size:18px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
            Select a Membership Plan
        </h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;" id="plans-container">
        @foreach($plans as $plan)
        @php $isSelected = ($selectedPlan && $selectedPlan->id === $plan->id); @endphp
        <div class="card-dark plan-card"
             data-plan-id="{{ $plan->id }}"
             data-plan-name="{{ $plan->name }}"
             data-plan-price="{{ $plan->price }}"
             onclick="selectPlan({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->price }})"
             style="cursor:pointer;border:2px solid {{ $isSelected ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};{{ $isSelected ? 'box-shadow:var(--glow-green);' : '' }}display:flex;flex-direction:column;transition:all .2s;position:relative;">
            
            @if($isSelected)
            <div class="plan-check" style="position:absolute;top:12px;right:12px;width:24px;height:24px;border-radius:50%;background:var(--color-primary-container);color:var(--color-on-primary);display:flex;align-items:center;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:16px;font-weight:bold;">check</span>
            </div>
            @endif

            <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:6px;">
                {{ $plan->name }}
            </div>
            <div style="font-family:var(--font-heading);font-size:32px;font-weight:700;color:var(--color-primary-container);margin-bottom:4px;">
                PKR {{ number_format($plan->price) }}
            </div>
            <div style="font-size:12px;color:var(--color-outline);margin-bottom:14px;">
                {{ $plan->duration_days }} days full access
            </div>

            @if(!empty($plan->features))
            <ul style="list-style:none;padding:0;margin:0 0 16px;font-size:12px;color:var(--color-on-surface-variant);display:flex;flex-direction:column;gap:6px;flex:1;">
                @foreach(array_slice($plan->features, 0, 3) as $feat)
                <li style="display:flex;align-items:center;gap:6px;">
                    <span class="material-symbols-outlined" style="font-size:14px;color:var(--color-primary-container);">check_circle</span>
                    {{ $feat }}
                </li>
                @endforeach
            </ul>
            @endif

            <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:{{ $isSelected ? 'var(--color-primary-container)' : 'var(--color-outline)' }};font-weight:600;margin-top:auto;">
                {{ $isSelected ? '● Selected Plan' : 'Click to Select' }}
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── STEP 2: CHOOSE PAYMENT METHOD ─────────────────────────── --}}
<div style="margin-bottom:36px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
        <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">/// STEP 2</span>
        <h2 style="font-family:var(--font-heading);font-size:18px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
            How Do You Want to Pay?
        </h2>
    </div>

    @if($enabledMethods->isEmpty())
        <div class="card-dark" style="text-align:center;padding:48px 20px;border:1px dashed var(--color-surface-highest);">
            <span class="material-symbols-outlined" style="font-size:44px;color:var(--color-outline);margin-bottom:10px;">money_off</span>
            <div style="font-family:var(--font-heading);font-size:17px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:6px;">
                Manual Online Methods Temporarily Unavailable
            </div>
            <p style="font-size:13px;color:var(--color-on-surface-variant);max-width:440px;margin:0 auto 16px;">
                Our manual payment channels are currently undergoing maintenance. Please visit the gym reception desk to pay and renew your membership.
            </p>
            <a href="{{ route('member.dashboard') }}" class="btn-ghost" style="padding:10px 20px;">Back to Dashboard</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:16px;margin-bottom:24px;" id="methods-container">
            @foreach($enabledMethods as $idx => $m)
            @php $isMethodSelected = ($idx === 0); @endphp
            <div class="card-dark method-card"
                 id="method-card-{{ $m->id }}"
                 data-method-id="{{ $m->id }}"
                 onclick="selectMethod({{ $m->id }})"
                 style="cursor:pointer;border:2px solid {{ $isMethodSelected ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};{{ $isMethodSelected ? 'box-shadow:var(--glow-green);' : '' }}display:flex;align-items:center;gap:14px;padding:18px;transition:all .2s;position:relative;">
                
                <div style="width:44px;height:44px;border-radius:10px;background:var(--color-surface-high);border:1px solid var(--color-surface-highest);display:flex;align-items:center;justify-content:center;color:var(--color-primary-container);flex-shrink:0;">
                    <span class="material-symbols-outlined" style="font-size:24px;">{{ $m->icon }}</span>
                </div>
                <div>
                    <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">
                        {{ $m->display_name }}
                    </div>
                    <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">
                        {{ $m->type === 'bank_transfer' ? ($m->bank_name ?? 'Bank Wire') : 'Mobile Account' }}
                    </div>
                </div>

                <div class="method-indicator" style="margin-left:auto;width:20px;height:20px;border-radius:50%;border:2px solid {{ $isMethodSelected ? 'var(--color-primary-container)' : 'var(--color-outline)' }};display:flex;align-items:center;justify-content:center;">
                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $isMethodSelected ? 'var(--color-primary-container)' : 'transparent' }};"></span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── METHOD DETAILS DISPLAY ──────────────────────────── --}}
        @foreach($enabledMethods as $idx => $m)
        <div class="method-detail-pane card-dark"
             id="method-detail-{{ $m->id }}"
             style="display:{{ $idx === 0 ? 'grid' : 'none' }};grid-template-columns:2fr 1fr;gap:24px;border:1px solid var(--color-surface-highest);margin-bottom:32px;">
            
            {{-- Left: Account Info & Breakdown --}}
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                    <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">info</span>
                    <div style="font-family:var(--font-heading);font-size:15px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);">
                        Payment Details for {{ $m->display_name }}
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:12px;background:var(--color-surface-container);padding:18px;border-radius:8px;border:1px solid var(--color-surface-highest);margin-bottom:18px;">
                    @if($m->type === 'bank_transfer')
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Bank Name:</span>
                            <span style="font-weight:600;color:var(--color-on-surface);">{{ $m->bank_name }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Account Title:</span>
                            <span style="font-weight:600;color:var(--color-on-surface);">{{ $m->account_name }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Account Number:</span>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-family:monospace;font-weight:700;color:var(--color-primary-container);font-size:14px;">{{ $m->account_number }}</span>
                                <button type="button" onclick="copyText('{{ $m->account_number }}', this)" class="btn-ghost btn-sm" style="padding:2px 8px;font-size:10px;">Copy</button>
                            </div>
                        </div>
                        @if($m->iban)
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">IBAN:</span>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-family:monospace;font-size:12px;color:var(--color-on-surface);">{{ $m->iban }}</span>
                                <button type="button" onclick="copyText('{{ $m->iban }}', this)" class="btn-ghost btn-sm" style="padding:2px 8px;font-size:10px;">Copy</button>
                            </div>
                        </div>
                        @endif
                        @if($m->branch)
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Branch:</span>
                            <span style="color:var(--color-on-surface-variant);">{{ $m->branch }}</span>
                        </div>
                        @endif
                    @else
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Account Name:</span>
                            <span style="font-weight:600;color:var(--color-on-surface);">{{ $m->account_name }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                            <span style="color:var(--color-outline);">Phone Number:</span>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-family:monospace;font-weight:700;color:var(--color-primary-container);font-size:15px;">{{ $m->phone }}</span>
                                <button type="button" onclick="copyText('{{ $m->phone }}', this)" class="btn-ghost btn-sm" style="padding:2px 8px;font-size:10px;">Copy</button>
                            </div>
                        </div>
                    @endif
                </div>

                @if($m->instructions)
                <div style="padding:12px 16px;border-radius:6px;background:rgba(57,255,20,.05);border-left:3px solid var(--color-primary-container);font-size:12px;color:var(--color-on-surface-variant);line-height:1.5;">
                    <strong>Instructions:</strong> {{ $m->instructions }}
                </div>
                @endif
            </div>

            {{-- Right: Amount Breakdown & QR Code (if available) --}}
            <div style="display:flex;flex-direction:column;justify-content:space-between;background:var(--color-surface-container);padding:20px;border-radius:8px;border:1px solid var(--color-surface-highest);">
                <div>
                    <div style="font-family:var(--font-heading);font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-outline);margin-bottom:12px;">
                        Exact Amount to Pay
                    </div>

                    <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;margin-bottom:16px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--color-outline);">Selected Plan:</span>
                            <span style="font-weight:600;color:var(--color-on-surface);" class="summary-plan-name">{{ $selectedPlan?->name }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--color-outline);">Original Price:</span>
                            <span style="color:var(--color-on-surface);">PKR <span class="summary-plan-price">{{ number_format($selectedPlan?->price ?? 0) }}</span></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--color-outline);">Discount:</span>
                            <span style="color:var(--color-primary-container);">PKR 0</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid var(--color-surface-highest);font-family:var(--font-heading);font-size:16px;font-weight:700;">
                            <span style="color:var(--color-on-surface);">Total Payable:</span>
                            <span style="color:var(--color-primary-container);">PKR <span class="summary-plan-total">{{ number_format($selectedPlan?->price ?? 0) }}</span></span>
                        </div>
                    </div>
                </div>

                @if($m->qr_image_url)
                <div style="text-align:center;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
                    <div style="font-size:11px;color:var(--color-outline);margin-bottom:8px;">Scan with Banking / Wallet App:</div>
                    <img src="{{ $m->qr_image_url }}" alt="Payment QR" style="width:130px;height:130px;object-fit:cover;border-radius:8px;border:1px solid var(--color-surface-highest);background:#fff;padding:4px;display:inline-block;">
                </div>
                @endif
            </div>

        </div>
        @endforeach

        {{-- ── STEP 3: SUBMIT YOUR PAYMENT ───────────────────────── --}}
        <div style="margin-bottom:36px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">/// STEP 3</span>
                <h2 style="font-family:var(--font-heading);font-size:18px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                    Submit Your Payment
                </h2>
            </div>

            <form method="POST" action="{{ route('member.renewals.store') }}" enctype="multipart/form-data" class="card-dark" style="border:1px solid var(--color-surface-highest);padding:28px;">
                @csrf

                {{-- Hidden Fields --}}
                <input type="hidden" name="membership_plan_id" id="form-plan-id" value="{{ $selectedPlan?->id }}">
                <input type="hidden" name="payment_method_id" id="form-method-id" value="{{ $enabledMethods->first()?->id }}">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                    <div>
                        <label class="form-label-dark">Amount Paid (PKR) <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="number" name="amount" id="form-amount" value="{{ old('amount', (int)($selectedPlan?->price ?? 0)) }}" min="1" step="1" class="form-control-dark" required>
                        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Default is the plan amount. Change if partial/special fee approved by desk.</div>
                        @error('amount')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Transaction ID / Reference Number <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}" placeholder="e.g. TRX-9821736412" class="form-control-dark" required>
                        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Enter the exact TID or transfer reference from your payment slip.</div>
                        @error('transaction_reference')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                    <div>
                        <label class="form-label-dark">Payment Date and Time <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="datetime-local" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}" class="form-control-dark" required>
                        @error('paid_at')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label-dark">Screenshot or Receipt Slip <span style="color:var(--color-primary-container);">*</span></label>
                        <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp,application/pdf" class="form-control-dark" style="padding:8px;" required>
                        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">JPG, PNG, WebP or PDF · max 3 MB. Stored privately on secure server.</div>
                        @error('screenshot')<div style="font-size:12px;color:var(--color-error);margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label class="form-label-dark">Optional Note</label>
                    <textarea name="note" class="form-control-dark form-textarea-dark" rows="2" placeholder="Any additional details or special requests...">{{ old('note') }}</textarea>
                </div>

                {{-- Warning & Submit Button --}}
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding-top:20px;border-top:1px solid var(--color-surface-highest);">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-outline);max-width:540px;">
                        <span class="material-symbols-outlined" style="font-size:18px;color:#facc15;">verified_user</span>
                        <span>Manual verification: Your membership will renew automatically as soon as gym administration verifies your receipt.</span>
                    </div>

                    <button type="submit" class="btn-primary" style="padding:14px 36px;font-size:14px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
<style>
@media(max-width:850px){
    .method-detail-pane { grid-template-columns: 1fr!important; }
}
</style>
<script>
let currentPlanId = {{ $selectedPlan?->id ?? 'null' }};
let currentMethodId = {{ $enabledMethods->first()?->id ?? 'null' }};

function selectPlan(id, name, price) {
    currentPlanId = id;
    document.getElementById('form-plan-id').value = id;
    document.getElementById('form-amount').value = Math.round(price);

    // Update summaries
    document.querySelectorAll('.summary-plan-name').forEach(el => el.innerText = name);
    document.querySelectorAll('.summary-plan-price').forEach(el => el.innerText = Number(price).toLocaleString());
    document.querySelectorAll('.summary-plan-total').forEach(el => el.innerText = Number(price).toLocaleString());

    // Highlight card
    document.querySelectorAll('.plan-card').forEach(card => {
        if (card.getAttribute('data-plan-id') == id) {
            card.style.borderColor = 'var(--color-primary-container)';
            card.style.boxShadow = 'var(--glow-green)';
            if (!card.querySelector('.plan-check')) {
                const check = document.createElement('div');
                check.className = 'plan-check';
                check.style.cssText = 'position:absolute;top:12px;right:12px;width:24px;height:24px;border-radius:50%;background:var(--color-primary-container);color:var(--color-on-primary);display:flex;align-items:center;justify-content:center;';
                check.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;font-weight:bold;">check</span>';
                card.appendChild(check);
            }
        } else {
            card.style.borderColor = 'var(--color-surface-highest)';
            card.style.boxShadow = 'none';
            const check = card.querySelector('.plan-check');
            if (check) check.remove();
        }
    });
}

function selectMethod(id) {
    currentMethodId = id;
    document.getElementById('form-method-id').value = id;

    // Show/hide detail panes
    document.querySelectorAll('.method-detail-pane').forEach(p => p.style.display = 'none');
    const activePane = document.getElementById('method-detail-' + id);
    if (activePane) activePane.style.display = 'grid';

    // Highlight method cards
    document.querySelectorAll('.method-card').forEach(card => {
        const dot = card.querySelector('.method-indicator span');
        const circle = card.querySelector('.method-indicator');
        if (card.getAttribute('data-method-id') == id) {
            card.style.borderColor = 'var(--color-primary-container)';
            card.style.boxShadow = 'var(--glow-green)';
            circle.style.borderColor = 'var(--color-primary-container)';
            dot.style.background = 'var(--color-primary-container)';
        } else {
            card.style.borderColor = 'var(--color-surface-highest)';
            card.style.boxShadow = 'none';
            circle.style.borderColor = 'var(--color-outline)';
            dot.style.background = 'transparent';
        }
    });
}

function copyText(val, btn) {
    navigator.clipboard.writeText(val).then(() => {
        const orig = btn.innerText;
        btn.innerText = 'Copied!';
        btn.style.color = 'var(--color-primary-container)';
        setTimeout(() => {
            btn.innerText = orig;
            btn.style.color = '';
        }, 2000);
    });
}
</script>
@endpush
@endsection
