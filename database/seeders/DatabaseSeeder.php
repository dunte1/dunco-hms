<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('  DUNCO HMS - DATABASE SEEDER');
        $this->command->info('==============================================');
        $this->command->info('');

        // Seed roles, permissions, and all demo data
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CompleteDemoSeeder::class);
        $this->call(AssignUserRolesSeeder::class);
        $this->call(GrantAdminAllPermissions::class);

        // Seed supplementary data
        $this->command->info('[+] Seeding supplementary data...');
        $this->call(CurrencySeeder::class);
        $this->call(ThemeSettingsSeeder::class);
        $this->call(ICD10CodeSeeder::class);
        $this->call(ShaServiceCodeSeeder::class);
        $this->call(ModuleSeeder::class);

        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('  ALL SEEDING COMPLETE!');
        $this->command->info('==============================================');
    }
}
