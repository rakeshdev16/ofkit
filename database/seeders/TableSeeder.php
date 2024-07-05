<?php

namespace Database\Seeders;

use App\Models\MemberRole;
use App\Models\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            'Kindergarten Teacher', 'Special Education Teacher', 'Art therapist', 'Bibliotherapist', 'Drama Therapist', 'Creativity and Expression', 'Music therapist', 'Movement therapist', 'Behaviour Analyst', 'Occupational Therapist', 'Children Neurologist', 'Assistant', 'Social Worker', 'Physiotherapist', 'Clinical Psychologist', 'Developmental Psychologist', 'Educational Psychologist', 'Clinical Psych. Major', 'Developmental Psych. Major', 'Educational Psych. Major', 'Children Psychiatrist', 'Speech therapist', 'Children development doctor', 'Children doctor'
        ];
        $roles = [
            'Professional Therapist', 'External Professional Guide', 'Internal Professional Guide', 'Kindergarten Manager', 'Cluster Manager', 'ABA Therapist', 'FT Therapist', 'Social Skills Therapist', 'DIR Guide'
        ];

        foreach ($professions as $name) {
            Profession::firstOrCreate(['name' => $name]);
        }
        
        foreach ($roles as $name) {
            MemberRole::firstOrCreate(['name' => $name]);
        }
    }
}
