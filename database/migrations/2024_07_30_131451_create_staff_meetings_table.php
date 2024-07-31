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
        Schema::create('staff_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('children_doc_id')->constrained('children_documentations')->onDelete('cascade');
            $table->integer('children_id')->nullable();
            $table->string('topic')->nullable();
            $table->string('discussion')->nullable();
            $table->string('decisions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_meetings');
    }
};
