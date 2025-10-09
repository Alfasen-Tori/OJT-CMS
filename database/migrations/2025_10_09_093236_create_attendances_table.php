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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_hte_id')->constrained('interns_hte')->onDelete('cascade');
            $table->date('date');
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->decimal('hours_rendered', 5, 2)->nullable()->comment('Calculated hours rendered for the day');
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for same intern_hte and date
            $table->unique(['intern_hte_id', 'date']);
            
            // Index for better performance on date queries
            $table->index(['date']);
            $table->index(['intern_hte_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};