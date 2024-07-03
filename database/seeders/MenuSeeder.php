<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['label' => 'Documentation', 'icon' => '', 'route' => '', 'subMenu' => []],
            ['label' => 'Children', 'icon' => '', 'route' => '', 'subMenu' => [
                ['lablel' => 'All Childrens', 'icon' => '', 'route' => ''],
                ['lablel' => 'Add Children', 'icon' => '', 'route' => ''],
            ]],
            ['label' => 'Staff', 'icon' => '', 'route' => '', 'subMenu' => [
                ['lablel' => 'All Staff', 'icon' => '', 'route' => ''],
                ['lablel' => 'Add Staff', 'icon' => '', 'route' => ''],
            ]],
            ['label' => 'Staff', 'icon' => '', 'route' => '', 'subMenu' => [
                ['lablel' => 'All Staff', 'icon' => '', 'route' => ''],
                ['lablel' => 'Add Staff', 'icon' => '', 'route' => ''],
            ]]
        ];
    }
}
