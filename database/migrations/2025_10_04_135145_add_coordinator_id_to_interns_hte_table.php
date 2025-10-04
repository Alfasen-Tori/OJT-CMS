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
        Schema::table('interns_hte', function (Blueprint $table) {
            // Add coordinator_id column (required)
            $table->foreignId('coordinator_id')->after('hte_id')->constrained('coordinators');
            
            // Add composite index for better query performance
            $table->index(['coordinator_id', 'hte_id']);
            $table->index(['coordinator_id', 'intern_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns_hte', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['coordinator_id', 'hte_id']);
            $table->dropIndex(['coordinator_id', 'intern_id']);
            
            // Drop the foreign key constraint
            $table->dropForeign(['coordinator_id']);
            
            // Drop the column
            $table->dropColumn('coordinator_id');
        });
    }
};