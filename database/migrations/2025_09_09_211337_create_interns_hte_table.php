<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternsHteTable extends Migration
{
    public function up()
    {
        Schema::create('interns_hte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns')->onDelete('cascade');
            $table->foreignId('hte_id')->constrained('htes')->onDelete('cascade');
            
            // Internship workflow status
            $table->enum('status', ['endorsed', 'processing',  'deployed', 'completed'])->default('endorsed');
            
            // Workflow timestamps
            $table->timestamp('endorsed_at')->useCurrent();
            $table->timestamp('deployed_at')->nullable();

            // Explicit internship timeline
            $table->date('start_date')->nullable(); // can be set manually or when deployed
            $table->date('end_date')->nullable();   // set when internship finishes
            
            $table->timestamps();

            // Prevent duplicate endorsement for the same intern and HTE
            $table->unique(['intern_id', 'hte_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('interns_hte');
    }
}
