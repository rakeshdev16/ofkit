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
        Schema::create('therapy_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->onDelete('cascade');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('day');
            $table->string('frequency_repeat')->nullable();
            $table->string('start')->nullable();
            $table->string('group_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('file')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('draft_name')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy_schedules');
    }
};
