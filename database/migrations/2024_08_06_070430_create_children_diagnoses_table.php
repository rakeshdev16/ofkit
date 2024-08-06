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
        Schema::create('children_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->foreignId('diagnosis_id')->constrained('diagnoses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_diagnoses');
    }
};
