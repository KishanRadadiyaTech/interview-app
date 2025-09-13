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
        Schema::table('interview_user', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('interview_user', 'status')) {
                $table->enum('status', ['invited', 'in_progress', 'completed'])->default('invited');
            }
            
            // Add invited_at column if it doesn't exist
            if (!Schema::hasColumn('interview_user', 'invited_at')) {
                $table->timestamp('invited_at')->useCurrent();
            }
            
            // Add started_at column if it doesn't exist
            if (!Schema::hasColumn('interview_user', 'started_at')) {
                $table->timestamp('started_at')->nullable();
            }
            
            // Add completed_at column if it doesn't exist
            if (!Schema::hasColumn('interview_user', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the columns in the down method to prevent data loss
        // If you need to rollback, create a new migration to handle it
    }
};
