# SMS Integration — Dunco HMS

Dunco HMS sends SMS through a single central service, `App\Services\SmsService`, used by every messaging path in the system: the Messaging UI, bulk sends, patient welcome messages, IPD discharge notices, appointment/payment reminders, and payment receipts (staff payments and M-Pesa callbacks).

## Active provider: TalkSasa BulkSMS

- Portal: https://bulksms.talksasa.com (Developers → API Documents)
- API: `POST https://bulksms.talksasa.com/api/v3/sms/send`
- Auth: Bearer token (`Authorization: Bearer {token}`)
- Sender ID: `TALK-SASA` (alphanumeric, max 11 chars; additional IDs are registered in the Talksasa portal and must be approved by the carrier)

Other providers (Twilio, Africa's Talking, Nexmo/Vonage) remain supported via the same service — switch with `SMS_PROVIDER`.

## Configuration (`.env`)

```env
SMS_PROVIDER=talksasa
TALKSASA_API_TOKEN=your-api-token
TALKSASA_SENDER_ID=TALK-SASA
TALKSASA_API_URL=https://bulksms.talksasa.com/api/v3
```

After changing env values run:

```bash
php artisan config:clear   # and config:cache in production
```

## How sending works

```
Caller (controller / job / listener)
  └─ SmsService::send($to, $message, $options)      app/Services/SmsService.php
       └─ match (SMS_PROVIDER)
            talksasa ─► sendViaTalksasa()
                         ├─ normalizes number (07xx/01xx/+254 → 254XXXXXXXXX)
                         ├─ POST {url}/sms/send  {recipient, sender_id, type: plain, message}
                         └─ success when response {status: "success"} → returns queue_uid as message_id
            twilio / africas_talking / nexmo ─► existing implementations
  └─ SmsService::sendBulk($recipients, $message) loops send() per recipient
  └─ SmsService::isConfigured() gates all automated sends
```

TalkSasa responses: `{status: "success", data: {queue_uid, status: "accepted", ...}}` or `{status: "error", message}`. Delivery status can be polled at the returned `check_status_url` (`GET /api/v3/sms/queue/{queue_uid}`).

**Cost tip:** keep messages ≤160 chars and use plain ASCII (no em dashes/emojis) so each SMS costs 1 credit instead of 2–3.

## Message templates

Templates live in the `message_templates` table (model `App\Models\MessageTemplate`) with `{placeholder}` variables, seeded by `database/seeders/MessageTemplateSeeder.php`:

| Template | Fired by |
|---|---|
| Patient Registration Welcome | Patient registration (`SendPatientWelcomeNotification` listener) |
| Patient Discharge | IPD discharge (`HandleDischargeCompletion` listener) |
| Appointment Reminder | `SendAppointmentReminders` job |
| Appointment Confirmation / Cancellation | Manual / bulk messaging UI |
| Payment Receipt | `PaymentsController` and `MpesaCallbackController` |
| Payment Reminder | `SendPaymentReminders` job (overdue invoices) |
| Lab Results Ready | Manual / bulk messaging UI |

Seeding (safe to re-run — uses `updateOrCreate`):

```bash
php artisan db:seed --class=MessageTemplateSeeder --force
```

Rendering: `MessageTemplate::renderByName('Payment Receipt', [...$vars], fallback: '...')` — every automated send has a hardcoded fallback if the template is missing. Templates are managed in the UI at **Messaging → Templates** (create/delete). The **Bulk** page has a template picker, character counter, and real multi-patient send (`sendBulk`).

## Send paths

| Path | Trigger |
|---|---|
| `POST /messaging/send` | Messaging UI (single or bulk patients) |
| `SendPatientWelcomeNotification` | `PatientRegistered` event |
| `HandleDischargeCompletion` | `DischargeCompleted` event (IPD discharge) |
| `SendAppointmentReminders` | Scheduled job — tomorrow's appointments |
| `SendPaymentReminders` | Scheduled job — overdue invoices |
| `PaymentsController::sendSmsReceipt` | Staff-recorded payments |
| `MpesaCallbackController::sendSmsReceipt` | M-Pesa STK callback |

## Testing

```bash
# Provider + credentials
php artisan tinker --execute="dump(app(\App\Services\SmsService::class)->getProvider(), app(\App\Services\SmsService::class)->isConfigured());"
# expect: "talksasa" / true

# Live send (1 credit)
php artisan tinker --execute="dump(app(\App\Services\SmsService::class)->send('07XXXXXXXX', 'Test from DUNCO HMS'));"
```

Provider failures are logged to `storage/logs/laravel.log` with the HTTP status and response body.

## Deployment notes

- Upload code changes, set the env vars on the server, `php artisan config:clear && php artisan config:cache`, seed templates.
- No migrations required (`message_templates` already exists) and no extra composer packages.
- The Talksasa token is a secret — keep it only in `.env`, never in code. Rotate it in the Talksasa portal if exposed.
