<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with default admin and staff users.
     */
    public function run(): void
    {
        $this->command->info('👤 Creating default users...');

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'security_setup_completed' => false,
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff',
            'email' => 'staff@inventaris.com',
            'password' => Hash::make('panelsibaraku'),
            'role' => 'staff',
            'is_active' => true,
            'security_setup_completed' => false,
        ]);

        $this->command->info('✅ Default users created');
        $this->command->info('   Admin: admin@inventaris.com / panelsibaraku');
        $this->command->info('   Staff: staff@inventaris.com / panelsibaraku');
    }
}
