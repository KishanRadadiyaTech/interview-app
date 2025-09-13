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
        // First, drop any existing constraints that might prevent us from modifying the table
        Schema::table('interview_user', function (Blueprint $table) {
            // Check if the status column exists and is an enum with the correct values
            if (Schema::hasColumn('interview_user', 'status')) {
                $connection = config('database.default');
                $db = config("database.connections.{$connection}.database");
                $tableName = 'interview_user';
                
                // Get the current column type
                $columnType = DB::selectOne(
                    "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = '{$db}' 
                    AND TABLE_NAME = '{$tableName}' 
                    AND COLUMN_NAME = 'status'"
                );
                
                // If the column exists but doesn't have the correct enum values, modify it
                if ($columnType && strpos($columnType->COLUMN_TYPE, "'invited','in_progress','completed'") === false) {
                    $table->enum('status', ['invited', 'in_progress', 'completed'])->default('invited')->change();
                }
            } else {
                // Add the status column if it doesn't exist
                $table->enum('status', ['invited', 'in_progress', 'completed'])->default('invited');
            }
            
            // Add other columns if they don't exist
            if (!Schema::hasColumn('interview_user', 'invited_at')) {
                $table->timestamp('invited_at')->useCurrent();
            }
            
            if (!Schema::hasColumn('interview_user', 'started_at')) {
                $table->timestamp('started_at')->nullable();
            }
            
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
