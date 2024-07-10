<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'environment', 'value' => 'local']);
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            TableSeeder::class,
        ]);
    }
}
