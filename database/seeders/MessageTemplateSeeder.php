<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Seed the standard HMS SMS templates.
     *
     * Variables use {placeholder} syntax and are rendered at send time.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Patient Registration Welcome',
                'type' => 'sms',
                'variables' => ['patient_name', 'facility_name', 'patient_id'],
                'content' => 'Hello {patient_name}, welcome to {facility_name}! Your registration is complete. Patient ID: {patient_id}. We wish you good health.',
            ],
            [
                'name' => 'Patient Discharge',
                'type' => 'sms',
                'variables' => ['patient_name', 'facility_name', 'date'],
                'content' => 'Dear {patient_name}, you have been discharged from {facility_name} on {date}. Your discharge summary is ready at reception. Wishing you a full recovery.',
            ],
            [
                'name' => 'Appointment Reminder',
                'type' => 'sms',
                'variables' => ['patient_name', 'doctor_name', 'date', 'time'],
                'content' => 'Dear {patient_name}, this is a reminder of your appointment with Dr. {doctor_name} on {date} at {time}. Please arrive 10 minutes early. Thank you.',
            ],
            [
                'name' => 'Appointment Confirmation',
                'type' => 'sms',
                'variables' => ['patient_name', 'doctor_name', 'date', 'time'],
                'content' => 'Hi {patient_name}, your appointment with Dr. {doctor_name} has been confirmed for {date} at {time}. Reply or call us to reschedule if needed.',
            ],
            [
                'name' => 'Payment Receipt',
                'type' => 'sms',
                'variables' => ['patient_name', 'facility_name', 'amount', 'invoice_number', 'date', 'method', 'balance'],
                'content' => 'Dear {patient_name}, we have received {amount} against Invoice {invoice_number} on {date} via {method}. Remaining balance: {balance}. Thank you - {facility_name}.',
            ],
            [
                'name' => 'Payment Reminder',
                'type' => 'sms',
                'variables' => ['patient_name', 'facility_name', 'balance', 'invoice_number', 'due_date'],
                'content' => 'Dear {patient_name}, this is a friendly reminder that {balance} is outstanding on Invoice {invoice_number}, due {due_date}. Please settle at reception or via M-Pesa. Thank you - {facility_name}.',
            ],
            [
                'name' => 'Lab Results Ready',
                'type' => 'sms',
                'variables' => ['patient_name', 'facility_name'],
                'content' => 'Dear {patient_name}, your lab results are ready for collection at {facility_name}. Please bring your Patient ID. For assistance call reception.',
            ],
            [
                'name' => 'Appointment Cancellation',
                'type' => 'sms',
                'variables' => ['patient_name', 'doctor_name', 'date', 'time', 'facility_name'],
                'content' => 'Dear {patient_name}, your appointment with Dr. {doctor_name} on {date} at {time} has been cancelled. Please contact {facility_name} to reschedule. We apologise for any inconvenience.',
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
