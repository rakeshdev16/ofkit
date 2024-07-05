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
            'admin' => [
                'dashboard', 'staff.index', 'staff.create', 'staff.store', 'staff.show', 'staff.edit', 'staff.update', 'staff.destroy', 'cluster.index', 'cluster.create', 'cluster.store', 'cluster.show', 'cluster.edit', 'cluster.update', 'cluster.destroy', 'kindergarten.index', 'kindergarten.create', 'kindergarten.store', 'kindergarten.show', 'kindergarten.edit', 'kindergarten.update', 'kindergarten.destroy', 'children.index', 'children.create', 'children.store', 'children.show', 'children.edit', 'children.update', 'children.destroy', 'therapy-schedule.index', 'staff-table.index', 'staff-table.create', 'staff-table.store', 'staff-table.edit', 'staff-table.update', 'staff-table.destroy'
            ],
            'manager' => ['dashboard', 'staff.index', 'children.index', 'therapy-schedule.index'],
            'therapist' => ['dashboard', 'staff.index', 'children.index', 'therapy-schedule.index']
        ];
        foreach ($roles as $name => $permission) {
            $role = Role::firstOrCreate(['name' => $name]);
            $role->givePermissionTo($permission);
        }
    }
}
