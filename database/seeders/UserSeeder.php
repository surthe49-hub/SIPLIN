<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with default admin and staff users.
     *
     * SECURITY NOTE: Default passwords are for local development only.
     * Change these before deploying to production. Ideally, in production,
     * remove this seeder entirely and create the first admin manually via
     * `php artisan tinker` or a one-time setup script.
     */
    public function run(): void
    {
        $this->command->info('Creating default users...');

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff',
            'email' => 'staff@inventaris.com',
            'password' => Hash::make('admin123'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        $this->command->info('Default users created:');
        $this->command->info('  Admin: admin@inventaris.com / admin123');
        $this->command->info('  Staff: staff@inventaris.com / admin123');
        $this->command->warn('WARNING: Change these passwords before production!');
    }
}