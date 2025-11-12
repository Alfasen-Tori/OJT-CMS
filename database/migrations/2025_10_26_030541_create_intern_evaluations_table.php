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
        // First, drop the old table if it exists
        Schema::dropIfExists('intern_evaluations');
        
        // Create the new table with job factors
        Schema::create('intern_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_hte_id')->constrained('interns_hte')->onDelete('cascade');
            
            // Job factor columns with percentages
            $table->decimal('quality_of_work', 5, 2)->comment('20% - Thoroughness, Accuracy, Neatness, Effectiveness');
            $table->decimal('dependability', 5, 2)->comment('15% - Ability to work with minimum supervision');
            $table->decimal('timeliness', 5, 2)->comment('15% - Able to complete work in allotted time');
            $table->decimal('attendance', 5, 2)->comment('15% - Regularity and punctuality');
            $table->decimal('cooperation', 5, 2)->comment('10% - Works well with everyone, good teamwork');
            $table->decimal('judgment', 5, 2)->comment('10% - Sound decisions, ability to evaluate factors');
            $table->decimal('personality', 5, 2)->comment('5% - Personal grooming, pleasant disposition');
            
            // Calculated total grade
            $table->decimal('total_grade', 5, 2)->comment('Calculated total grade (0-100)');
            
            $table->text('comments')->nullable();
            $table->date('evaluation_date');
            $table->timestamps();

            // Add unique constraint to prevent multiple evaluations for the same deployment
            $table->unique('intern_hte_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_evaluations');
        
        // Optionally: Recreate the old table structure if needed for rollback
        // Schema::create('intern_evaluations', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('intern_hte_id')->constrained('interns_hte')->onDelete('cascade');
        //     $table->decimal('grade', 5, 2)->comment('0-100 scale');
        //     $table->text('comments')->nullable();
        //     $table->date('evaluation_date');
        //     $table->timestamps();
        //     $table->unique('intern_hte_id');
        // });
    }
};