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
            if (!Schema::hasColumn('interview_user', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_user', function (Blueprint $table) {
            if (Schema::hasColumn('interview_user', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};
