<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@tridi.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('tridipassword'),
                'is_superadmin' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
