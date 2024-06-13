<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => ['dashboard', 'staff.index', 'children.index', 'children.create'],
            'manager' => ['dashboard', 'staff.index', 'children.index', 'children.create'],
            'therapist' => ['dashboard', 'staff.index', 'children.index', 'children.create']
        ];
        foreach ($roles as $name => $permission) {
            $role = Role::firstOrCreate(['name' => $name]);
            $role->givePermissionTo($permission);
        }
    }
}
