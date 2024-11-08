<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('childrens', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('kindergartens', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('kindergarten_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('framework_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('professions', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('member_roles', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('associations', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('parents_statuses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('hmos', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('functionalities', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('statuses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('file_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('intervention_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('document_and_approvals', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('childrens', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('kindergartens', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('kindergarten_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('framework_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('professions', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('member_roles', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('associations', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('parents_statuses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('hmos', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('functionalities', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('statuses', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('file_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('intervention_types', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        Schema::table('document_and_approvals', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }
};
