<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin.sleimanji@yopmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin Sleimanji',
                'email' => 'admin.sleimanji@yopmail.com',
                'password' => Hash::make('12345678')
            ]);
            $admin->assignRole('admin');
        }
        if (!User::where('email', 'manager.sleimanji@yopmail.com')->exists()) {
            $manager = User::create([
                'name' => 'Manager Sleimanji',
                'email' => 'manager.sleimanji@yopmail.com',
                'password' => Hash::make('12345678')
            ]);
            $manager->assignRole('manager');
        }
        if (!User::where('email', 'therapist.sleimanji@yopmail.com')->exists()) {
            $therapist = User::create([
                'name' => 'Therapist Sleimanji',
                'email' => 'therapist.sleimanji@yopmail.com',
                'password' => Hash::make('12345678')
            ]);
            $therapist->assignRole('therapist');
        }
    }
}
