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
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns')->onDelete('cascade');
            $table->tinyInteger('week_no')->comment('Week number e.g., 1, 2, 3...');
            $table->string('report_path')->nullable()->comment('File path to the uploaded report');
            $table->timestamp('submitted_at')->nullable()->useCurrent();
            $table->timestamps();

            // Unique constraint to prevent duplicate reports for same intern and week
            $table->unique(['intern_id', 'week_no']);
            
            // Index for better performance
            $table->index(['intern_id']);
            $table->index(['week_no']);
            $table->index(['submitted_at']);
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};