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
        Schema::create('kindergartens', function (Blueprint $table) {
            $table->id();
            $table->integer('cluster_id')->nullable();
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->string('framework')->nullable();
            $table->string('type')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kindergartens');
    }
};
