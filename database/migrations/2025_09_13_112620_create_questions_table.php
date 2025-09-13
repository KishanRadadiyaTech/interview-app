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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['text', 'video', 'multiple_choice', 'code']);
            $table->json('options')->nullable(); // For multiple choice options or other question-specific settings
            $table->integer('order')->default(0);
            $table->integer('time_limit')->nullable(); // in seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
