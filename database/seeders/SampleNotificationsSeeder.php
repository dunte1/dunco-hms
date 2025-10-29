<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleNotificationsSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No user found. Please create a user first.');
            return;
        }

        $notifications = [
            [
                'type' => 'App\\Notifications\\AppointmentNotification',
                'data' => json_encode([
                    'title' => 'New Appointment Scheduled',
                    'message' => 'You have a new appointment scheduled for tomorrow at 10:00 AM with Dr. Sarah Johnson',
                    'type' => 'appointment'
                ]),
                'read_at' => null,
            ],
            [
                'type' => 'App\\Notifications\\PaymentNotification',
                'data' => json_encode([
                    'title' => 'Payment Received',
                    'message' => 'Payment of $500.00 has been successfully received from patient John Doe for Invoice #INV-2024-001',
                    'type' => 'payment'
                ]),
                'read_at' => null,
            ],
            [
                'type' => 'App\\Notifications\\LabResultNotification',
                'data' => json_encode([
                    'title' => 'Lab Results Available',
                    'message' => 'Lab test results for patient Jane Smith (Blood Test - Complete) are now available for review',
                    'type' => 'lab_result'
                ]),
                'read_at' => null,
            ],
            [
                'type' => 'App\\Notifications\\PrescriptionNotification',
                'data' => json_encode([
                    'title' => 'Prescription Ready',
                    'message' => 'Prescription for patient Michael Brown has been prepared and is ready for pickup at Pharmacy Counter 2',
                    'type' => 'prescription'
                ]),
                'read_at' => now(),
            ],
            [
                'type' => 'App\\Notifications\\AppointmentNotification',
                'data' => json_encode([
                    'title' => 'Appointment Reminder',
                    'message' => 'Reminder: Patient appointment with Dr. Emily Davis is scheduled in 1 hour',
                    'type' => 'appointment'
                ]),
                'read_at' => now(),
            ],
            [
                'type' => 'App\\Notifications\\SystemNotification',
                'data' => json_encode([
                    'title' => 'System Maintenance Notice',
                    'message' => 'Scheduled system maintenance will occur tonight from 11:00 PM to 1:00 AM. Please save all work.',
                    'type' => 'system'
                ]),
                'read_at' => now(),
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => $notification['type'],
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => $notification['data'],
                'read_at' => $notification['read_at'],
                'created_at' => now()->subHours(rand(1, 48)),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Sample notifications created successfully!');
    }
}

