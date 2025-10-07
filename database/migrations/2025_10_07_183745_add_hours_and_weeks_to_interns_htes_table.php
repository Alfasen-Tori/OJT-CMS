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
            $table->integer('no_of_hours')->nullable()->after('end_date'); // Total hours required for the internship
            $table->integer('no_of_weeks')->nullable()->after('no_of_hours'); // Calculated weeks based on hours
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns_hte', function (Blueprint $table) {
            $table->dropColumn(['no_of_hours', 'no_of_weeks']);
        });
    }
};
