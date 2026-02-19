<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $hrManager = User::firstOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'HR Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $hrManager->assignRole('hr_manager');

        $this->command->info('  Users seeded:');
        $this->command->info('    admin@example.com   / password  (role: admin)');
        $this->command->info('    hr@example.com      / password  (role: hr_manager)');
    }
}
