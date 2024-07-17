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
        Schema::create('children_medical_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->boolean('food_allergie')->nullable();
            $table->longText('food_allergie_detail')->nullable();
            $table->boolean('medicine')->nullable();
            $table->string('medicine_detail')->nullable();
            $table->string('medicine_name')->nullable();
            $table->string('dosage_and_timing')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_medical_information');
    }
};
