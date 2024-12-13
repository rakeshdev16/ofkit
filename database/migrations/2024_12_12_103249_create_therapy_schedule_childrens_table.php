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
        Schema::create('therapy_schedule_childrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapy_schedule_id')->nullable()->constrained('therapy_schedules')->onDelete('cascade');
            $table->foreignId('children_id')->nullable()->constrained('childrens')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy_schedule_childrens');
    }
};
