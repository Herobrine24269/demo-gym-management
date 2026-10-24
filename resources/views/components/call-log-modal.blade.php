{{-- ══════════════════════════════════════════════════════════════════
     REUSABLE CALL LOG & CONTACT MODAL
     Used by Admin & Trainer member directories
     ══════════════════════════════════════════════════════════════════ --}}
<div id="call-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);align-items:center;justify-content:center;padding:16px;"
     onclick="if(event.target===this)closeCallModal()">

    <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:14px;padding:24px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 32px 80px rgba(0,0,0,.7);animation:fadeInUp .15s ease;">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;border-bottom:1px solid var(--color-surface-highest);padding-bottom:14px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:8px;background:rgba(57,255,20,.15);border:1px solid rgba(57,255,20,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined" style="font-size:20px;color:var(--color-primary-container);">call</span>
                </div>
                <div>
                    <div style="font-family:var(--font-heading);font-size:16px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);letter-spacing:.04em;">Call & Contact Member</div>
                    <div id="call-member-header-sub" style="font-size:12px;color:var(--color-outline);margin-top:2px;"></div>
                </div>
            </div>
            <button type="button" onclick="closeCallModal()" style="background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:24px;line-height:1;padding:0;margin-top:-2px;" title="Close">×</button>
        </div>

        {{-- Direct Contact Options (Phone & Email) --}}
        <div style="background:var(--color-surface-container);border:1px solid var(--color-surface-highest);border-radius:10px;padding:14px 16px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                <div>
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--color-outline);margin-bottom:2px;">Phone Number</div>
                    <div id="call-phone-display" style="font-family:monospace;font-size:15px;color:var(--color-on-surface);font-weight:600;letter-spacing:.05em;"></div>
                </div>
                <div id="call-phone-actions" style="display:flex;gap:8px;align-items:center;">
                    <a id="call-tel-link" href="#" class="btn-primary" style="padding:7px 14px;font-size:11px;" target="_blank">
                        <span class="material-symbols-outlined" style="font-size:15px;">call</span> Dial
                    </a>
                    <a id="call-wa-link" href="#" target="_blank" class="btn-ghost" style="padding:7px 14px;font-size:11px;border:1px solid rgba(57,255,20,0.3);color:var(--color-primary-container);">
                        <span class="material-symbols-outlined" style="font-size:15px;">chat</span> WhatsApp
                    </a>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding-top:10px;border-top:1px solid var(--color-surface-highest);">
                <div>
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--color-outline);margin-bottom:2px;">Email Address</div>
                    <div id="call-email-display" style="font-size:13px;color:var(--color-on-surface-variant);"></div>
                </div>
                <a id="call-email-link" href="#" class="btn-ghost" style="padding:7px 14px;font-size:11px;color:var(--color-secondary);border:1px solid rgba(129,207,255,0.3);">
                    <span class="material-symbols-outlined" style="font-size:15px;">mail</span> Send Email
                </a>
            </div>
        </div>

        {{-- Past Call History --}}
        <div style="margin-bottom:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span class="form-label-dark" style="margin:0;font-size:11px;">Recent Call Logs</span>
                <span id="call-history-count" style="font-size:11px;color:var(--color-outline);background:var(--color-surface-container);padding:2px 8px;border-radius:10px;">0 logged</span>
            </div>
            <div id="call-history-list" style="max-height:140px;overflow-y:auto;display:flex;flex-direction:column;gap:6px;border:1px solid var(--color-surface-highest);border-radius:8px;padding:8px;background:var(--color-surface-lowest);">
                {{-- Injected dynamically --}}
            </div>
        </div>

        {{-- Form to Log a New Call --}}
        <form method="POST" id="call-log-form" action="">
            @csrf

            <div style="margin-bottom:16px;">
                <label class="form-label-dark" style="margin-bottom:8px;display:block;">Record Call Outcome *</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach([
                        ['answered',   'check_circle', 'Answered',   'var(--color-primary-container)', 'rgba(57,255,20,.1)'],
                        ['no_answer',  'call_end',     'No Answer',  'var(--color-outline)',           'rgba(133,150,124,.15)'],
                        ['will_return','schedule',     'Will Return','var(--color-secondary)',         'rgba(129,207,255,.1)'],
                        ['cancelled',  'block',        'Cancelled',  'var(--color-error)',             'rgba(255,180,171,.1)'],
                    ] as [$val, $icon, $label, $color, $bg])
                    <label class="outcome-label" style="display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;background:var(--color-surface-container);border:2px solid transparent;cursor:pointer;transition:border-color .12s,background .12s;">
                        <input type="radio" name="outcome" value="{{ $val }}" required style="display:none;" onchange="selectCallOutcome(this, '{{ $color }}', '{{ $bg }}')">
                        <span class="material-symbols-outlined" style="font-size:16px;color:{{ $color }};flex-shrink:0;">{{ $icon }}</span>
                        <span style="font-size:12px;font-weight:600;color:var(--color-on-surface);">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label-dark">Call Notes <span style="color:var(--color-outline);font-weight:400;font-size:11px;">(optional)</span></label>
                <textarea name="notes" class="form-control-dark" style="min-height:70px;margin-top:6px;resize:vertical;" placeholder="e.g. Discussed workout schedule, payment renewal reminder, etc."></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeCallModal()" class="btn-ghost" style="padding:9px 18px;">Cancel</button>
                <button type="submit" class="btn-primary" style="padding:9px 20px;">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span> Save Call Log
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<script>
function triggerCallLog(button) {
    const memberId = button.getAttribute('data-member-id');
    const name     = button.getAttribute('data-name') || 'Member';
    const code     = button.getAttribute('data-code') || '';
    const phone    = button.getAttribute('data-phone') || '';
    const email    = button.getAttribute('data-email') || '';
    const postUrl  = button.getAttribute('data-post-url') || '';
    let logs = [];
    try {
        logs = JSON.parse(button.getAttribute('data-logs') || '[]');
    } catch(e) {
        logs = [];
    }
    openCallModal(memberId, name, code, phone, email, postUrl, logs);
}

function openCallModal(memberId, name, code, phone, email, postUrl, logs) {
    document.getElementById('call-member-header-sub').textContent = name + (code ? ' (' + code + ')' : '');
    document.getElementById('call-email-display').textContent = email || 'No email on file';
    document.getElementById('call-email-link').href = email
        ? 'mailto:' + encodeURIComponent(email) + '?subject=' + encodeURIComponent('Kinetic Apex Gym — Message for ' + name)
        : '#';
    document.getElementById('call-email-link').style.display = email ? 'inline-flex' : 'none';

    // Phone handling
    const phoneDisplay = document.getElementById('call-phone-display');
    const phoneActions = document.getElementById('call-phone-actions');
    const telLink      = document.getElementById('call-tel-link');
    const waLink       = document.getElementById('call-wa-link');

    if (phone && phone.trim() !== '') {
        phoneDisplay.textContent = phone;
        phoneDisplay.style.color = 'var(--color-on-surface)';
        phoneActions.style.display = 'flex';
        telLink.href = 'tel:' + phone.replace(/\s+/g, '');

        // Format clean phone for WhatsApp
        let cleanPhone = phone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '92' + cleanPhone.substring(1); // Standard default or direct
        }
        waLink.href = 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent('Hi ' + name + ', contacting you from Kinetic Apex Gym.');
        waLink.style.display = 'inline-flex';
    } else {
        phoneDisplay.textContent = 'No phone number on record';
        phoneDisplay.style.color = 'var(--color-outline)';
        phoneActions.style.display = 'none';
    }

    // Set form action
    document.getElementById('call-log-form').action = postUrl;

    // Reset outcome options
    document.querySelectorAll('#call-modal .outcome-label').forEach(l => {
        l.style.borderColor = 'transparent';
        l.style.background  = 'var(--color-surface-container)';
    });
    document.querySelectorAll('#call-modal input[name="outcome"]').forEach(r => r.checked = false);
    const notesInput = document.querySelector('#call-modal textarea[name="notes"]');
    if (notesInput) notesInput.value = '';

    // Render past call logs
    const historyList  = document.getElementById('call-history-list');
    const historyCount = document.getElementById('call-history-count');
    historyList.innerHTML = '';

    if (logs && logs.length > 0) {
        historyCount.textContent = logs.length + (logs.length === 1 ? ' log' : ' logs');
        logs.forEach(log => {
            const row = document.createElement('div');
            row.style.cssText = 'padding:8px 10px;background:var(--color-surface-container);border-radius:6px;font-size:12px;border:1px solid rgba(255,255,255,0.04);';

            let badgeColor = 'var(--color-primary-container)';
            let badgeBg = 'rgba(57,255,20,.15)';
            if (log.outcome === 'no_answer') {
                badgeColor = 'var(--color-outline)';
                badgeBg = 'rgba(133,150,124,.2)';
            } else if (log.outcome === 'will_return') {
                badgeColor = 'var(--color-secondary)';
                badgeBg = 'rgba(129,207,255,.15)';
            } else if (log.outcome === 'cancelled') {
                badgeColor = 'var(--color-error)';
                badgeBg = 'rgba(255,180,171,.15)';
            }

            const formattedOutcome = (log.outcome || '').replace('_', ' ').toUpperCase();

            row.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:3px;">
                    <span style="display:inline-block;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;font-family:var(--font-heading);letter-spacing:.05em;background:${badgeBg};color:${badgeColor};">${formattedOutcome}</span>
                    <span style="font-size:11px;color:var(--color-outline);">${log.date} · by ${escapeHtml(log.caller || 'Staff')}</span>
                </div>
                ${log.notes ? `<div style="color:var(--color-on-surface-variant);font-size:11.5px;margin-top:4px;word-break:break-word;">${escapeHtml(log.notes)}</div>` : ''}
            `;
            historyList.appendChild(row);
        });
    } else {
        historyCount.textContent = '0 logged';
        historyList.innerHTML = '<div style="text-align:center;padding:12px;color:var(--color-outline);font-size:11.5px;">No previous call logs recorded for this member.</div>';
    }

    const modal = document.getElementById('call-modal');
    modal.style.display = 'flex';
}

function closeCallModal() {
    const modal = document.getElementById('call-modal');
    if (modal) modal.style.display = 'none';
}

function selectCallOutcome(radio, color, bg) {
    document.querySelectorAll('#call-modal .outcome-label').forEach(l => {
        l.style.borderColor = 'transparent';
        l.style.background  = 'var(--color-surface-container)';
    });
    radio.parentElement.style.borderColor = color;
    radio.parentElement.style.background  = bg;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCallModal();
});
</script>
