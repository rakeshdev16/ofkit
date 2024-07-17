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
        Schema::create('children_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->string('father_name')->nullable();
            $table->string('father_telephone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_telephone')->nullable();
            $table->string('family_status')->nullable();
            $table->string('name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('telephone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_parents');
    }
};
