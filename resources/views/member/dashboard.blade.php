@extends('layouts.dashboard')
@section('title', 'My Dashboard')

@section('content')
@php
    $ms      = $member->activeMembership;
    $streak  = $member->streak;
    $wp      = $member->workoutPlans->where('status','active')->first();
    $diet    = $member->dietAssignments->first()?->dietPlan;
    $ptPack  = $member->ptSessions->where('status','active')->first();
@endphp

<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow">
            <span class="eyebrow-dot"></span>
            <span class="eyebrow-text">MEMBER PORTAL</span>
        </div>
        <h1 class="page-title">Welcome, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
        <p class="page-title-sub">{{ $member->member_code }} · Member since {{ $member->joined_at->format('F Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
        @if($ms)
        <div style="text-align:right;">
            <div class="badge-active" style="font-size:12px;padding:4px 12px;">{{ $ms->plan->name }}</div>
            <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Expires {{ $ms->end_date->format('d M Y') }} · {{ $ms->daysRemaining() }}d left</div>
        </div>
        @endif
        <a href="{{ route('member.renewals.index') }}" class="btn-primary" style="padding:10px 18px;font-size:12px;">
            <span class="material-symbols-outlined" style="font-size:16px;">autorenew</span>
            Renew Plan
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:32px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">This Month</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">calendar_month</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-primary-container);margin-top:4px;">{{ $attendanceThisMonth }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Sessions attended</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Current Streak</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">local_fire_department</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-primary-container);margin-top:4px;">{{ $streak?->current_streak ?? 0 }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Days in a row · Best: {{ $streak?->longest_streak ?? 0 }}d</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">Days Left</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:{{ $ms && $ms->daysRemaining() <= 7 ? 'var(--color-error)' : 'var(--color-on-surface)' }};">event_available</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:{{ $ms && $ms->daysRemaining() <= 7 ? 'var(--color-error)' : 'var(--color-on-surface)' }};margin-top:4px;">{{ $ms?->daysRemaining() ?? '—' }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">{{ $ms ? $ms->plan->name : 'No active plan' }}</div>
    </div>

    @if($ptPack)
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div class="stat-label">PT Sessions</div>
            <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-secondary);">fitness_center</span>
        </div>
        <div style="font-family:var(--font-heading);font-size:48px;font-weight:700;color:var(--color-secondary);margin-top:4px;">{{ $ptPack->sessionsRemaining() }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">Remaining of {{ $ptPack->total_sessions }}</div>
    </div>
    @endif
</div>

{{-- Last 14 days attendance visual --}}
<div class="card-dark" style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="section-title" style="font-size:16px;">Attendance — Last 14 Days</div>
        <a href="{{ route('member.attendance.index') }}" class="btn-ghost btn-sm">Full History</a>
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap;">
        @foreach(range(13, 0) as $d)
        @php
            $date = today()->subDays($d);
            $key  = $date->format('Y-m-d');
            $hit  = isset($attendanceMap[$key]);
        @endphp
        <div style="flex:1;min-width:40px;text-align:center;">
            <div style="height:32px;border-radius:4px;background:{{ $hit ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};box-shadow:{{ $hit ? 'var(--glow-green)' : 'none' }};transition:all .2s;" title="{{ $date->format('D d M') . ($hit ? ' — Attended' : ' — Absent') }}"></div>
            <div style="font-size:10px;color:var(--color-outline);margin-top:4px;font-family:var(--font-heading);letter-spacing:.04em;">{{ $date->format('D')[0] }}</div>
        </div>
        @endforeach
    </div>
    <div style="display:flex;align-items:center;gap:16px;margin-top:12px;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--color-on-surface-variant);">
            <span style="width:12px;height:12px;border-radius:2px;background:var(--color-primary-container);display:inline-block;"></span> Attended
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--color-on-surface-variant);">
            <span style="width:12px;height:12px;border-radius:2px;background:var(--color-surface-highest);display:inline-block;"></span> Absent
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    {{-- Workout Plan --}}
    <div class="card-dark">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-secondary);">fitness_center</span>
                <div class="section-title" style="font-size:15px;">My Workout Plan</div>
            </div>
        </div>
        @if($wp)
        <div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;color:var(--color-on-surface);text-transform:uppercase;margin-bottom:4px;">{{ $wp->title }}</div>
            <div style="font-size:12px;color:var(--color-outline);margin-bottom:12px;">{{ $wp->weeks }} weeks · by {{ $wp->trainer->user->name ?? '—' }}</div>
            <span class="badge-active">Active</span>
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <span class="material-symbols-outlined" style="font-size:36px;color:var(--color-outline);">fitness_center</span>
            <div style="font-size:12px;color:var(--color-outline);margin-top:8px;">No workout plan assigned yet. Contact your trainer.</div>
        </div>
        @endif
    </div>

    {{-- Diet Plan --}}
    <div class="card-dark">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">restaurant</span>
                <div class="section-title" style="font-size:15px;">My Diet Plan</div>
            </div>
        </div>
        @if($diet)
        <div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;color:var(--color-on-surface);text-transform:uppercase;margin-bottom:4px;">{{ $diet->title }}</div>
            <div style="display:flex;align-items:center;gap:16px;margin-top:8px;flex-wrap:wrap;">
                @foreach([['🔥',$diet->total_calories,'kcal'],['💪',$diet->total_protein_g.'g','Protein'],['🌾',$diet->total_carbs_g.'g','Carbs'],['🥑',$diet->total_fat_g.'g','Fat']] as [$em,$val,$lbl])
                <div style="text-align:center;">
                    <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-surface);">{{ $em }} {{ $val }}</div>
                    <div style="font-size:10px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <span class="material-symbols-outlined" style="font-size:36px;color:var(--color-outline);">restaurant</span>
            <div style="font-size:12px;color:var(--color-outline);margin-top:8px;">No diet plan assigned yet. Contact your trainer.</div>
        </div>
        @endif
    </div>
</div>

{{-- ── PAYMENT METHODS & BILLING SECTION ──────────────────────── --}}
<div style="margin-top:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
        <div>
            <div style="display:flex;align-items:center;gap:8px;color:var(--color-primary-container);margin-bottom:4px;">
                <span style="font-family:var(--font-heading);font-size:12px;">///</span>
                <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">FINANCIAL & ACCESS DIRECTIVE</span>
            </div>
            <h2 style="font-family:var(--font-heading);font-size:20px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                Payment Methods & Billing Records
            </h2>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="font-size:12px;color:var(--color-outline);">Total Paid to Date:</div>
            <div style="font-family:var(--font-heading);font-size:20px;font-weight:700;color:var(--color-primary-container);">
                PKR {{ number_format($totalPaid ?? 0) }}
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:5fr 7fr;gap:16px;">
        {{-- Card 1: Active Payment Method & Facility Payment Channels --}}
        <div class="card-dark" style="display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid var(--color-surface-highest);padding-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">credit_card</span>
                        <div class="section-title" style="font-size:15px;">Active Billing Channel</div>
                    </div>
                    <span class="badge-active" style="font-size:10px;">VERIFIED</span>
                </div>

                @if($ms)
                {{-- Current Membership Payment Pill --}}
                <div style="padding:16px;border-radius:8px;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);margin-bottom:18px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-size:11px;color:var(--color-outline);font-family:var(--font-heading);text-transform:uppercase;letter-spacing:.06em;">Current Membership Method</span>
                        @php
                            $pm = $ms->payment_method ?? 'cash';
                            $pmLabels = ['cash' => 'Cash at Desk', 'card' => 'Debit/Credit Card', 'bank_transfer' => 'Bank Transfer / Raast', 'other' => 'Digital Wallet'];
                            $pmIcons  = ['cash' => 'payments', 'card' => 'credit_card', 'bank_transfer' => 'account_balance', 'other' => 'account_balance_wallet'];
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:var(--color-primary-container);font-weight:600;background:rgba(57,255,20,.12);padding:2px 8px;border-radius:4px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">{{ $pmIcons[$pm] ?? 'payments' }}</span>
                            {{ $pmLabels[$pm] ?? ucfirst($pm) }}
                        </span>
                    </div>

                    <div style="display:flex;align-items:baseline;justify-content:space-between;">
                        <div>
                            <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;color:var(--color-on-surface);text-transform:uppercase;">
                                {{ $ms->plan->name }}
                            </div>
                            <div style="font-size:12px;color:var(--color-outline);margin-top:2px;">
                                Valid: {{ $ms->start_date->format('d M') }} — {{ $ms->end_date->format('d M Y') }}
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--color-on-surface);">
                                PKR {{ number_format($ms->amount_paid) }}
                            </div>
                            <div style="font-size:10px;color:var(--color-primary-container);font-weight:600;">PAID IN FULL</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Accepted Gym Payment Options for Renewals --}}
                <div style="font-family:var(--font-heading);font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:10px;">
                    Available Facility Payment Channels
                </div>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    {{-- Desk Cash & Card --}}
                    <div style="display:flex;align-items:flex-start;gap:12px;padding:12px;border-radius:8px;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);">
                        <div style="width:34px;height:34px;border-radius:6px;background:var(--color-surface-high);display:flex;align-items:center;justify-content:center;color:var(--color-primary-container);flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:18px;">point_of_sale</span>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);">Front Desk POS / Cash Counter</div>
                            <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">Visa, Mastercard, PayPak, or Cash at reception terminal (24/7).</div>
                        </div>
                    </div>

                    {{-- Bank Transfer / Raast --}}
                    <div style="display:flex;align-items:flex-start;gap:12px;padding:12px;border-radius:8px;background:var(--color-surface-container);border:1px solid var(--color-surface-highest);">
                        <div style="width:34px;height:34px;border-radius:6px;background:var(--color-surface-high);display:flex;align-items:center;justify-content:center;color:var(--color-secondary);flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:18px;">account_balance</span>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);">Online Bank Transfer & Raast</div>
                            <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">
                                Bank: <strong style="color:var(--color-on-surface-variant);">Meezan Bank Ltd</strong> • Title: <strong style="color:var(--color-on-surface-variant);">Kinetic Apex</strong>
                            </div>
                            <div style="font-family:monospace;font-size:11px;color:var(--color-primary-container);margin-top:3px;background:rgba(0,0,0,.3);padding:2px 6px;border-radius:4px;display:inline-block;">
                                IBAN: PK72MEZN0001928374650101
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:16px;padding:12px 14px;border-radius:6px;background:rgba(57,255,20,.06);border:1px solid rgba(57,255,20,.2);font-size:12px;color:var(--color-on-surface-variant);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">info</span>
                    <span>Paid via Easypaisa, JazzCash, or Bank?</span>
                </div>
                <a href="{{ route('member.renewals.index') }}" style="color:var(--color-primary-container);font-weight:600;text-decoration:none;font-size:11px;font-family:var(--font-heading);letter-spacing:.05em;text-transform:uppercase;">
                    Submit Payment Proof Online →
                </a>
            </div>
        </div>

        {{-- Card 2: Payment History & Receipts Table --}}
        <div class="card-dark" style="display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;border-bottom:1px solid var(--color-surface-highest);padding-bottom:12px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-secondary);">receipt_long</span>
                    <div class="section-title" style="font-size:15px;">Transaction History & Receipts</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:11px;color:var(--color-outline);">{{ $recentPayments->count() }} records</span>
                    <a href="{{ route('member.payments.index') }}" class="btn-ghost btn-sm" style="font-size:11px;padding:3px 8px;">View All →</a>
                </div>
            </div>

            <div style="overflow-x:auto;flex:1;">
                <table class="table-dark" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th style="text-align:right;">Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                        @php
                            $pmMethod = strtolower($payment->payment_method ?? 'cash');
                            $pmBadgeColor = match($pmMethod) {
                                'card'          => 'var(--color-secondary)',
                                'bank_transfer' => 'var(--color-primary-container)',
                                default         => 'var(--color-on-surface-variant)',
                            };
                        @endphp
                        <tr>
                            <td style="color:var(--color-outline);font-size:11px;">
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y') : $payment->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div style="font-weight:600;color:var(--color-on-surface);">{{ ucfirst($payment->type ?? 'Membership') }}</div>
                                <div style="font-size:10px;color:var(--color-outline);font-family:monospace;">INV-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;color:{{ $pmBadgeColor }};">
                                    <span style="width:6px;height:6px;border-radius:50%;background:{{ $pmBadgeColor }};"></span>
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td style="font-family:var(--font-heading);font-weight:700;color:var(--color-on-surface);">
                                PKR {{ number_format($payment->amount) }}
                            </td>
                            <td>
                                <span class="badge-active" style="font-size:9px;padding:2px 6px;">{{ strtoupper($payment->status ?? 'PAID') }}</span>
                            </td>
                            <td style="text-align:right;">
                                <button type="button"
                                        class="btn-ghost btn-sm btn-icon"
                                        title="View Official Receipt"
                                        onclick="viewPaymentReceipt('INV-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}', '{{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : $payment->created_at->format('d M Y') }}', '{{ ucfirst($payment->type ?? 'Membership') }}', 'PKR {{ number_format($payment->amount) }}', '{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}', '{{ $member->user->name }}', '{{ $member->member_code }}')">
                                    <span class="material-symbols-outlined" style="font-size:16px;">receipt</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:32px;color:var(--color-outline);">
                                No payment records logged for this account yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── RECEIPT MODAL ──────────────────────────────────────────── --}}
<div id="receipt-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--color-surface-container);border:1px solid var(--color-surface-highest);border-radius:12px;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,.7);overflow:hidden;position:relative;" id="receipt-printable">
        {{-- Header --}}
        <div style="background:var(--color-surface-lowest);padding:24px;border-bottom:1px dashed var(--color-surface-highest);text-align:center;position:relative;">
            <button type="button" onclick="closeReceiptModal()" style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--color-outline);font-size:22px;cursor:pointer;">×</button>
            <div style="width:38px;height:38px;border-radius:6px;background:var(--color-primary-container);color:var(--color-on-primary);display:inline-flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:20px;font-weight:700;margin-bottom:8px;">K</div>
            <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;letter-spacing:.06em;color:var(--color-on-surface);text-transform:uppercase;">KINETIC APEX</div>
            <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.08em;">Official Payment Voucher</div>
        </div>

        {{-- Receipt Body --}}
        <div style="padding:24px;display:flex;flex-direction:column;gap:14px;">
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Invoice #:</span>
                <span style="font-family:monospace;font-weight:700;color:var(--color-primary-container);" id="modal-invoice"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Date & Time:</span>
                <span style="color:var(--color-on-surface);" id="modal-date"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Member Name:</span>
                <span style="font-weight:600;color:var(--color-on-surface);" id="modal-member-name"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Member Code:</span>
                <span style="font-family:monospace;color:var(--color-on-surface);" id="modal-member-code"></span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <span style="color:var(--color-outline);">Payment Method:</span>
                <span style="color:var(--color-on-surface);" id="modal-method"></span>
            </div>

            <div style="border-top:1px dashed var(--color-surface-highest);border-bottom:1px dashed var(--color-surface-highest);padding:14px 0;margin:6px 0;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--color-on-surface);" id="modal-desc"></div>
                    <div style="font-size:10px;color:var(--color-outline);">Status: Verified & Cleared</div>
                </div>
                <div style="font-family:var(--font-heading);font-size:22px;font-weight:700;color:var(--color-primary-container);" id="modal-amount"></div>
            </div>

            <div style="text-align:center;font-size:10px;color:var(--color-outline);line-height:1.4;">
                Thank you for training with Kinetic Apex. This is a computer generated receipt.
            </div>
        </div>

        {{-- Footer --}}
        <div style="background:var(--color-surface-low);padding:14px 24px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--color-surface-highest);">
            <button type="button" class="btn-ghost btn-sm" onclick="closeReceiptModal()">Close</button>
            <button type="button" class="btn-primary btn-sm" onclick="window.print()">
                <span class="material-symbols-outlined" style="font-size:16px;">print</span> Print Receipt
            </button>
        </div>
    </div>
</div>

@push('scripts')
<style>
@media(max-width:900px){
    div[style*="grid-template-columns:1fr 1fr"],
    div[style*="grid-template-columns:5fr 7fr"]{
        grid-template-columns:1fr!important;
    }
}
@media print {
    body * { visibility: hidden; }
    #receipt-modal, #receipt-printable, #receipt-printable * { visibility: visible; }
    #receipt-modal { position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: none; }
}
</style>
<script>
function viewPaymentReceipt(inv, date, desc, amt, method, name, code) {
    document.getElementById('modal-invoice').textContent = inv;
    document.getElementById('modal-date').textContent = date;
    document.getElementById('modal-desc').textContent = desc;
    document.getElementById('modal-amount').textContent = amt;
    document.getElementById('modal-method').textContent = method;
    document.getElementById('modal-member-name').textContent = name;
    document.getElementById('modal-member-code').textContent = code;
    const modal = document.getElementById('receipt-modal');
    modal.style.display = 'flex';
}
function closeReceiptModal() {
    document.getElementById('receipt-modal').style.display = 'none';
}
</script>
@endpush
@endsection

