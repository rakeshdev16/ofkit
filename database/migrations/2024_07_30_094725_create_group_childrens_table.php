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
        Schema::create('group_childrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_documentation_id')->constrained('children_documentations')->onDelete('cascade');
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->boolean('participated')->nullable();
            $table->string('reason')->nullable();
            $table->longText('description')->nullable();
            $table->longText('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_childrens');
    }
};
