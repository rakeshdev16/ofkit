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
        Schema::create('childrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kindergarten_id')->nullable()->constrained('kindergartens')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('identification')->unique()->nullable();
            $table->string('gender')->nullable();
            $table->date('dob');
            $table->string('age')->nullable();
            $table->string('address')->nullable();
            $table->integer('functionality_id')->nullable();
            $table->integer('diagnosis_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('hmo_id')->nullable();
            $table->string('service_start_date')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childrens');
    }
};
