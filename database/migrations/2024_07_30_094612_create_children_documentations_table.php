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
        Schema::create('children_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('kindergarten_id')->constrained('kindergartens')->onDelete('cascade');
            $table->boolean('occured')->nullable();
            $table->longText('occured_description')->nullable();
            $table->string('occured_reason')->nullable();
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_documentations');
    }
};
