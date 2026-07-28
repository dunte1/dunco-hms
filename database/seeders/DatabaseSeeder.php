<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Step 1: Create roles and permissions
        $this->command->info('📋 Creating roles and permissions...');
        $this->call(RolesAndPermissionsSeeder::class);
        
        // Step 2: Create admin user if doesn't exist
        $this->command->info('👤 Creating/updating admin user...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Admin user: ' . $admin->email);
        
        // Step 3: Grant all permissions to admin
        $this->command->info('🔐 Granting all permissions to admin...');
        $this->call(GrantAdminAllPermissions::class);
        
        // Step 4: Seed other data
        $this->command->info('📦 Seeding additional data...');
        $this->call(CurrencySeeder::class);
        $this->call(ThemeSettingsSeeder::class);
        $this->call(ModuleSeeder::class);
        
        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('🔑 Admin credentials:');
        $this->command->info('   Email: admin@example.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('💡 Please change the admin password after first login!');
    }
}
