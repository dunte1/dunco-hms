@once
<div id="mpesa-payment-modal" class="mpesa-modal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(10,20,15,.6);backdrop-filter:blur(2px);">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:min(430px,92vw);background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.35);font-family:Arial,sans-serif;color:#1f2937;">

        <div id="mpesa-modal-header" style="background:#00a94f;color:#fff;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:18px;font-weight:bold;">Pay with M-Pesa</div>
                <div style="font-size:12px;opacity:.9;" id="mpesa-modal-subtitle">Confirm payment on your phone</div>
            </div>
            <button type="button" onclick="closeMpesaModal()" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;font-size:16px;cursor:pointer;line-height:1;">&times;</button>
        </div>

        {{-- Step 1: details + pay --}}
        <div id="mpesa-step-initiate" style="padding:24px;">
            <div id="mpesa-fee-summary" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;margin-bottom:18px;">
                <div id="mpesa-item-name-label" style="font-size:13px;color:#166534;margin-bottom:4px;">Payment</div>
                <div id="mpesa-item-name" style="font-size:16px;font-weight:bold;color:#14532d;"></div>
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:8px;">
                    <span style="font-size:13px;color:#166534;">Amount due</span>
                    <span id="mpesa-amount" style="font-size:22px;font-weight:bold;color:#00a94f;"></span>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">M-Pesa phone number</label>
                <input id="mpesa-phone" type="tel" value="254"
                       style="width:100%;padding:11px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:15px;box-sizing:border-box;outline:none;">
                <div style="font-size:12px;color:#6b7280;margin-top:6px;">The STK prompt will be sent to this Safaricom number.</div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeMpesaModal()"
                        style="flex:1;padding:12px;border:1px solid #d1d5db;background:#fff;color:#374151;border-radius:8px;font-size:15px;cursor:pointer;">Cancel</button>
                <button type="button" id="mpesa-pay-btn"
                        style="flex:2;padding:12px;background:#00a94f;border:none;color:#fff;border-radius:8px;font-size:15px;font-weight:bold;cursor:pointer;"><span id="mpesa-pay-btn-text">Send Payment Request</span></button>
            </div>

            <div id="mpesa-initiate-error" style="display:none;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:10px 12px;font-size:13px;margin-top:14px;"></div>
        </div>

        {{-- Step 2: waiting for user to enter PIN --}}
        <div id="mpesa-step-waiting" style="display:none;padding:36px 24px;text-align:center;">
            <div style="width:56px;height:56px;border:4px solid #bbf7d0;border-top-color:#00a94f;border-radius:50%;margin:0 auto 18px;animation:mpesaSpin 1s linear infinite;"></div>
            <div style="font-size:16px;font-weight:bold;color:#14532d;margin-bottom:6px;">Waiting for payment&hellip;</div>
            <div style="font-size:13px;color:#6b7280;line-height:1.5;" id="mpesa-waiting-text">Enter your M-Pesa PIN on your phone when prompted.<br>We will confirm automatically.</div>
            <div style="margin-top:16px;font-size:12px;color:#9ca3af;" id="mpesa-poll-note"></div>
        </div>

        {{-- Step 3: result --}}
        <div id="mpesa-step-result" style="display:none;padding:28px 24px;text-align:center;">
            <div id="mpesa-result-icon" style="width:64px;height:64px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:32px;"></div>
            <div id="mpesa-result-title" style="font-size:18px;font-weight:bold;margin-bottom:8px;"></div>
            <div id="mpesa-result-message" style="font-size:13px;color:#6b7280;line-height:1.5;margin-bottom:18px;"></div>

            <div id="mpesa-result-details" style="display:none;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;text-align:left;font-size:13px;color:#374151;margin-bottom:18px;"></div>

            <div id="mpesa-result-actions">
                <button type="button" onclick="closeMpesaModal()" id="mpesa-done-btn"
                        style="width:100%;padding:12px;background:#00a94f;border:none;color:#fff;border-radius:8px;font-size:15px;font-weight:bold;cursor:pointer;">Done</button>
            </div>
        </div>

        <style>
            @keyframes mpesaSpin { to { transform: rotate(360deg); } }
        </style>
    </div>
</div>
@endonce

<script>
(function () {
    if (window.__mpesaModalLoaded) return;
    window.__mpesaModalLoaded = true;

    var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    function post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        }).then(function (r) { return r.json(); });
    }

    function get(url) {
        return fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) { return r.json(); });
    }

    var modalEl = document.getElementById('mpesa-payment-modal');
    var current = null;
    var pollTimer = null;

    window.openMpesaModal = function (config) {
        current = config || {};
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }

        document.getElementById('mpesa-item-name').textContent = current.itemName || 'Payment';
        document.getElementById('mpesa-item-name-label').textContent = current.feeLabel || 'Payment';
        document.getElementById('mpesa-amount').textContent = (current.currencySymbol || 'KSh ') + (Number(current.amount) || 0).toFixed(2);
        document.getElementById('mpesa-phone').value = current.phone || '254';

        showStep('initiate');
        hide('mpesa-initiate-error');
        document.getElementById('mpesa-pay-btn').disabled = false;
        document.getElementById('mpesa-pay-btn-text').textContent = 'Send Payment Request';
        modalEl.style.display = 'block';
    };

    window.closeMpesaModal = function () {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        modalEl.style.display = 'none';
        current = null;
    };

    document.getElementById('mpesa-pay-btn').addEventListener('click', initiateMpesaPayment);

    function initiateMpesaPayment() {
        if (!current) return;
        var phone = document.getElementById('mpesa-phone').value.trim();
        if (!/^(\+?254|0)?[17]\d{8}$/.test(phone.replace(/\s/g, ''))) {
            showInitiateError('Enter a valid Safaricom phone number (e.g. 0712345678).');
            return;
        }
        hide('mpesa-initiate-error');
        document.getElementById('mpesa-pay-btn').disabled = true;
        document.getElementById('mpesa-pay-btn-text').textContent = 'Sending\u2026';

        var payload = {
            patient_id: current.patientId,
            fee_type: current.feeType || 'invoice_payment',
            item_name: current.itemName || null,
            amount: Number(current.amount) || 0,
            phone: phone,
            source_type: current.sourceType || null,
            source_id: current.sourceId || null,
            invoice_id: current.invoiceId || null
        };

        post('{{ route("hms.mpesa.initiate") }}', payload).then(function (resp) {
            if (resp.success) {
                current.paymentId = resp.payment_id;
                showStep('waiting');
                startPolling(resp.payment_id);
            } else {
                document.getElementById('mpesa-pay-btn').disabled = false;
                document.getElementById('mpesa-pay-btn-text').textContent = 'Send Payment Request';
                showInitiateError(resp.message || 'Could not start M-Pesa payment. Try again.');
            }
        }).catch(function () {
            document.getElementById('mpesa-pay-btn').disabled = false;
            document.getElementById('mpesa-pay-btn-text').textContent = 'Send Payment Request';
            showInitiateError('Network error. Could not reach the server.');
        });
    }

    function startPolling(paymentId) {
        var attempts = 0;
        document.getElementById('mpesa-poll-note').textContent = '';
        pollTimer = setInterval(function () {
            attempts++;
            if (attempts % 6 === 0) {
                document.getElementById('mpesa-poll-note').textContent = 'Still waiting\u2026 If prompted on your phone, enter your PIN.';
            }
            get('{{ route("hms.mpesa.status", ["payment" => "__PID__"]) }}'.replace('__PID__', paymentId)).then(function (resp) {
                if (!resp.success || resp.status === 'pending') return;
                clearInterval(pollTimer);
                pollTimer = null;
                handleResult(resp.status, resp);
            });
        }, 5000);
    }

    function handleResult(status, resp) {
        var outcomes = {
            completed: {
                icon: '\u2713', iconBg: '#dcfce7', iconColor: '#16a34a',
                title: 'Payment Successful',
                message: 'Your M-Pesa payment has been confirmed.'
            },
            cancelled: {
                icon: '\u2715', iconBg: '#fef3c7', iconColor: '#d97706',
                title: 'Payment Cancelled',
                message: 'You cancelled the payment on your phone. No money was deducted.'
            },
            timeout: {
                icon: '\u23F3', iconBg: '#fee2e2', iconColor: '#dc2626',
                title: 'Payment Timed Out',
                message: 'We did not receive confirmation in time. The request may have expired.'
            },
            failed: {
                icon: '\u2715', iconBg: '#fee2e2', iconColor: '#dc2626',
                title: 'Payment Failed',
                message: 'The payment was not completed. Please check your balance and try again.'
            }
        };

        var o = outcomes[status] || outcomes.failed;

        var icon = document.getElementById('mpesa-result-icon');
        icon.style.background = o.iconBg;
        icon.style.color = o.iconColor;
        icon.textContent = o.icon;

        document.getElementById('mpesa-result-title').textContent = o.title;
        document.getElementById('mpesa-result-message').textContent = o.message;

        var details = document.getElementById('mpesa-result-details');
        if (status === 'completed') {
            var rows = [
                ['Amount', (current.currencySymbol || 'KSh ') + (Number(resp.amount) || 0).toFixed(2)],
                ['M-Pesa Receipt', resp.mpesa_receipt || 'N/A'],
                ['Fee Type', current.feeLabel || 'Payment']
            ];
            details.innerHTML = rows.map(function (r) {
                return '<div style="display:flex;justify-content:space-between;padding:5px 0;"><span style="color:#6b7280;">' + r[0] + '</span><span style="font-weight:600;">' + r[1] + '</span></div>';
            }).join('');
            details.style.display = 'block';
            document.getElementById('mpesa-done-btn').textContent = 'Done';
        } else {
            details.style.display = 'none';
            document.getElementById('mpesa-done-btn').textContent = 'Close';
        }

        showStep('result');

        if (status === 'completed' && typeof current.onSuccess === 'function') {
            current.onSuccess(current, resp);
        }
    }

    function showStep(name) {
        ['initiate', 'waiting', 'result'].forEach(function (s) {
            document.getElementById('mpesa-step-' + s).style.display = (s === name) ? 'block' : 'none';
        });
    }

    function showInitiateError(msg) {
        var el = document.getElementById('mpesa-initiate-error');
        el.textContent = msg;
        el.style.display = 'block';
    }

    function hide(id) { document.getElementById(id).style.display = 'none'; }
})();
</script>