<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }
        
        // Clear existing attendance for today
        Attendance::whereDate('date', $today)->delete();
        
        $statuses = ['present', 'absent', 'leave', 'late'];
        $checkInTimes = ['08:00', '08:15', '08:30', '09:00', '09:15'];
        
        foreach ($users as $index => $user) {
            // Make most users present, some on leave, few absent
            $status = match(true) {
                $index % 10 === 0 => 'leave',      // 10% on leave
                $index % 15 === 0 => 'absent',     // ~7% absent
                $index % 7 === 0 => 'late',        // ~14% late
                default => 'present'               // ~70% present
            };
            
            $checkIn = $status === 'absent' ? null : $checkInTimes[array_rand($checkInTimes)];
            $checkOut = null;
            
            // Add check out time for some present staff
            if ($status === 'present' && $index % 3 === 0) {
                $checkOut = '17:00';
            }
            
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'hours_worked' => $checkOut ? 540 : 0, // 9 hours in minutes
                'notes' => $status === 'leave' ? 'Scheduled leave' : ($status === 'absent' ? 'Unplanned absence' : null),
            ]);
        }
        
        $this->command->info('Sample attendance data created successfully!');
        $this->command->info('Total records: ' . Attendance::whereDate('date', $today)->count());
        $this->command->info('Present: ' . Attendance::whereDate('date', $today)->where('status', 'present')->count());
        $this->command->info('On Leave: ' . Attendance::whereDate('date', $today)->where('status', 'leave')->count());
        $this->command->info('Absent: ' . Attendance::whereDate('date', $today)->where('status', 'absent')->count());
    }
}
