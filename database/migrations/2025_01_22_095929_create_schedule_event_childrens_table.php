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
        Schema::create('schedule_event_childrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_event_id')->nullable()->constrained('schedule_events')->onDelete('cascade');
            $table->foreignId('children_id')->nullable()->constrained('childrens')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_event_childrens');
    }
};
