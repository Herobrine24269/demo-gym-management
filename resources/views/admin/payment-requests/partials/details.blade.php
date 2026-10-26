<div style="display:flex;flex-direction:column;gap:20px;">
    {{-- Header with status badge --}}
    <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--color-surface-highest);padding-bottom:16px;">
        <div>
            <div style="font-size:11px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">
                PAYMENT REQUEST #{{ $submission->id }}
            </div>
            <div style="font-size:18px;font-weight:700;color:var(--color-on-surface);margin-top:2px;">
                {{ $submission->plan?->name ?? 'Membership Renewal' }}
            </div>
        </div>
        <div>
            @if($submission->isPending())
                <span class="badge-accent" style="background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);font-size:12px;padding:4px 10px;border-radius:20px;font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;margin-right:2px;">hourglass_empty</span> Pending Review
                </span>
            @elseif($submission->isApproved())
                <span class="badge-accent" style="background:rgba(57,255,20,0.12);color:var(--color-primary);border:1px solid rgba(57,255,20,0.3);font-size:12px;padding:4px 10px;border-radius:20px;font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;margin-right:2px;">check_circle</span> Approved
                </span>
            @else
                <span class="badge-accent" style="background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);font-size:12px;padding:4px 10px;border-radius:20px;font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;margin-right:2px;">cancel</span> Rejected
                </span>
            @endif
        </div>
    </div>

    {{-- 2-column info layout --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        {{-- Member Card --}}
        <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:8px;padding:14px;">
            <div style="font-size:10px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;margin-bottom:8px;">
                MEMBER DETAILS
            </div>
            <div style="font-size:14px;font-weight:700;color:var(--color-on-surface);">
                {{ $submission->member?->user?->name ?? 'Unknown Member' }}
            </div>
            <div style="font-size:12px;color:var(--color-outline);margin-top:4px;line-height:1.6;">
                <strong>Code:</strong> {{ $submission->member?->member_code ?? ('MEM-' . str_pad($submission->member_id, 4, '0', STR_PAD_LEFT)) }}<br>
                <strong>Email:</strong> {{ $submission->member?->user?->email ?? 'N/A' }}<br>
                <strong>Phone:</strong> {{ $submission->member?->user?->phone ?? 'N/A' }}
            </div>
        </div>

        {{-- Payment Transaction Card --}}
        <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:8px;padding:14px;">
            <div style="font-size:10px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;margin-bottom:8px;">
                TRANSACTION DETAILS
            </div>
            <div style="font-size:12px;color:var(--color-outline);line-height:1.6;">
                <strong>Amount:</strong> <span style="font-size:14px;font-weight:800;color:var(--color-primary);">Rs. {{ number_format($submission->amount) }}</span><br>
                <strong>Method:</strong> <span style="color:var(--color-on-surface);">{{ $submission->paymentMethod?->display_name ?? 'Manual' }}</span><br>
                <strong>Transaction ID:</strong> <span style="font-family:monospace;font-weight:700;color:#fff;">{{ $submission->transaction_reference }}</span><br>
                <strong>Paid At:</strong> {{ $submission->paid_at ? \Carbon\Carbon::parse($submission->paid_at)->format('d M Y, h:i A') : 'N/A' }}<br>
                <strong>Submitted:</strong> {{ $submission->created_at->format('d M Y, h:i A') }}
            </div>
        </div>
    </div>

    {{-- Member Note if present --}}
    @if($submission->note)
        <div style="background:var(--color-surface-low);border-left:3px solid var(--color-primary);border-radius:4px;padding:12px 14px;font-size:12px;color:var(--color-on-surface-variant);">
            <strong style="color:var(--color-on-surface);">Member Note:</strong> {{ $submission->note }}
        </div>
    @endif

    {{-- Rejection reason if rejected --}}
    @if($submission->isRejected())
        <div style="background:rgba(239,68,68,0.1);border-left:3px solid #ef4444;border-radius:4px;padding:12px 14px;font-size:12px;color:#fca5a5;">
            <strong style="color:#ef4444;">Rejection Reason:</strong> {{ $submission->rejection_reason }}
            <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">
                Reviewed by {{ $submission->reviewer?->name ?? 'Admin' }} on {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->format('d M Y, h:i A') : '' }}
            </div>
        </div>
    @endif

    {{-- Receipt info if approved --}}
    @if($submission->isApproved())
        <div style="background:rgba(57,255,20,0.08);border:1px solid rgba(57,255,20,0.25);border-radius:8px;padding:14px;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:10px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-primary);text-transform:uppercase;">
                    OFFICIAL RECEIPT GENERATED
                </div>
                <div style="font-size:15px;font-weight:800;color:#fff;margin-top:2px;">
                    {{ $submission->receipt_number }}
                </div>
                <div style="font-size:11px;color:var(--color-outline);margin-top:2px;">
                    Approved by {{ $submission->reviewer?->name ?? 'Admin' }} on {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->format('d M Y, h:i A') : '' }}
                </div>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.payment-requests.receipt', $submission) }}" target="_blank" class="btn-secondary" style="font-size:12px;padding:6px 12px;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">receipt</span> View Receipt
                </a>
                <a href="{{ route('admin.payment-requests.receipt-pdf', $submission) }}" class="btn-primary" style="font-size:12px;padding:6px 12px;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">download</span> PDF
                </a>
            </div>
        </div>
    @endif

    {{-- Zoomable Payment Proof Screenshot --}}
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <div style="font-size:11px;font-family:var(--font-heading);letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">
                PAYMENT PROOF SCREENSHOT
            </div>
            <a href="{{ route('payment-submissions.screenshot', $submission) }}" target="_blank" style="font-size:11px;color:var(--color-primary);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                <span class="material-symbols-outlined" style="font-size:14px;">open_in_new</span> Open Full Size
            </a>
        </div>
        <div style="background:#0a0a0a;border:1px solid var(--color-surface-highest);border-radius:8px;padding:12px;text-align:center;overflow:hidden;">
            @php
                $isPdf = str_ends_with(strtolower($submission->screenshot_path), '.pdf');
            @endphp
            @if($isPdf)
                <div style="padding:40px;color:var(--color-outline);">
                    <span class="material-symbols-outlined" style="font-size:48px;color:#ef4444;">picture_as_pdf</span>
                    <div style="margin-top:10px;font-size:13px;color:#fff;">PDF Payment Document Uploaded</div>
                    <a href="{{ route('payment-submissions.screenshot', $submission) }}" target="_blank" class="btn-secondary" style="margin-top:14px;display:inline-flex;text-decoration:none;font-size:12px;">
                        View PDF Document
                    </a>
                </div>
            @else
                <img src="{{ route('payment-submissions.screenshot', $submission) }}" alt="Payment Proof" style="max-width:100%;max-height:480px;object-fit:contain;border-radius:4px;cursor:zoom-in;transition:transform 0.2s;" onclick="this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)'" title="Click to zoom in/out">
                <div style="font-size:11px;color:var(--color-outline);margin-top:8px;">Tip: Click on the image to zoom in/out, or open full size in a new tab.</div>
            @endif
        </div>
    </div>

    {{-- Actions for Admin if pending --}}
    @if($submission->isPending() && auth()->user()->isAdmin())
        <div style="display:flex;gap:12px;border-top:1px solid var(--color-surface-highest);padding-top:20px;margin-top:8px;">
            {{-- Approve Form --}}
            <form method="POST" action="{{ route('admin.payment-requests.approve', $submission) }}" style="flex:1;" onsubmit="return confirm('Are you sure you want to approve this payment of Rs. {{ number_format($submission->amount) }}? This will renew the membership and generate official receipt.');">
                @csrf
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px;">
                    <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
                    Approve & Renew Membership
                </button>
            </form>

            {{-- Reject Button (triggers reject reason section) --}}
            <button type="button" class="btn-secondary" style="border-color:#ef4444;color:#ef4444;padding:12px 20px;font-size:14px;" onclick="document.getElementById('reject-form-{{ $submission->id }}').style.display = document.getElementById('reject-form-{{ $submission->id }}').style.display === 'none' ? 'block' : 'none';">
                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                Reject...
            </button>
        </div>

        {{-- Hidden Reject Form --}}
        <div id="reject-form-{{ $submission->id }}" style="display:none;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:16px;margin-top:12px;">
            <form method="POST" action="{{ route('admin.payment-requests.reject', $submission) }}">
                @csrf
                <label class="form-label-dark" style="color:#ef4444;">Reason for Rejection *</label>
                <textarea name="rejection_reason" class="form-control-dark form-textarea-dark" rows="3" placeholder="Explain why payment could not be verified (e.g. Transaction ID did not match bank statement, receipt image was unreadable, wrong amount paid)..." required minlength="3" maxlength="500"></textarea>
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
                    <button type="button" class="btn-secondary" style="font-size:12px;" onclick="document.getElementById('reject-form-{{ $submission->id }}').style.display = 'none';">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" style="background:#ef4444;color:#fff;border-color:#ef4444;font-size:12px;">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    @elseif(!auth()->user()->isAdmin())
        <div style="font-size:12px;color:var(--color-outline);background:var(--color-surface-low);padding:10px 14px;border-radius:6px;text-align:center;">
            <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;">lock</span>
            Read-only mode: Only Administrators can approve or reject payment requests.
        </div>
    @endif
</div>
