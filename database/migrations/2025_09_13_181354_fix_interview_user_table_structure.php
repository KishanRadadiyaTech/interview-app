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
        // Create a temporary table to store the existing data
        Schema::create('interview_user_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['invited', 'in_progress', 'completed'])->default('invited');
            $table->timestamp('invited_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['interview_id', 'user_id']);
        });
        
        // Copy data from the old table to the new table
        DB::statement('INSERT INTO interview_user_temp (interview_id, user_id, created_at, updated_at) SELECT interview_id, user_id, created_at, updated_at FROM interview_user');
        
        // Drop the old table
        Schema::dropIfExists('interview_user');
        
        // Rename the new table to the original name
        Schema::rename('interview_user_temp', 'interview_user');
        
        // Add back any indexes or foreign keys if needed
        Schema::table('interview_user', function (Blueprint $table) {
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a destructive migration, so we can't easily roll it back
        // You would need to restore from a backup if you need to undo this
    }
};
