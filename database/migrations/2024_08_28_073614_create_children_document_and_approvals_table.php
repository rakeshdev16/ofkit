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
        Schema::create('children_document_and_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_id')->constrained('childrens')->onDelete('cascade');
            $table->string('document')->nullable();
            $table->foreignId('file_type_id')->constrained('file_types')->onDelete('cascade');
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_document_and_approvals');
    }
};
