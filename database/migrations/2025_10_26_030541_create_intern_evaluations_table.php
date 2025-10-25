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
        Schema::create('intern_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_hte_id')->constrained('interns_hte')->onDelete('cascade');
            $table->decimal('grade', 5, 2)->comment('0-100 scale');
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
    }
};