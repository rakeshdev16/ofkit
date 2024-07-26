<?php

namespace Database\Seeders;

use App\Models\Association;
use App\Models\Diagnosis;
use App\Models\DocumentAndApproval;
use App\Models\FrameworkType;
use App\Models\Functionality;
use App\Models\Hmo;
use App\Models\InterventionType;
use App\Models\KindergartenType;
use App\Models\MemberRole;
use App\Models\ParentsStatus;
use App\Models\Profession;
use App\Models\Status;
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

        foreach (['Official', 'Non-official', 'Private'] as $name) {
            KindergartenType::firstOrCreate(['name' => $name]);
        }

        foreach (['Communications', 'Residence', 'External'] as $name) {
            FrameworkType::firstOrCreate(['name' => $name]);
        }

        foreach (['Tabam', 'Matia'] as $name) {
            Association::firstOrCreate(['name' => $name]);
        }

        foreach (['Married', 'Divorced', 'Separated', 'Other'] as $name) {
            ParentsStatus::firstOrCreate(['name' => $name]);
        }

        foreach (['Clalit', 'Macabi', 'Meuhedet', 'Leomit', 'Other'] as $name) {
            Hmo::firstOrCreate(['name' => $name]);
        }

        foreach (['Autism', 'Mental Disability', 'ADHD'] as $name) {
            Diagnosis::firstOrCreate(['name' => $name]);
        }

        foreach (['High', 'Other'] as $name) {
            Functionality::firstOrCreate(['name' => $name]);
        }

        foreach (['New', 'Continuing'] as $name) {
            Status::firstOrCreate(['name' => $name]);
        }

        foreach (['Individual', 'Parental Guidance', 'Group', 'Staff Meeting', 'Break', 'Guidance', 'Preparation', 'Other'] as $name) {
            InterventionType::firstOrCreate(['name' => $name]);
        }

        foreach (['Initial Evaluation', 'Final Evaluation', 'Declaration of receipt of Tabam', 'Waiver of confidentiality'] as $name) {
            DocumentAndApproval::firstOrCreate(['name' => $name]);
        }
    }
}
