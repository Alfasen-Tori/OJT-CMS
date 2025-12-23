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
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token');
            $table->enum('role', ['admin', 'coordinator', 'intern', 'hte'])->nullable();
            $table->timestamp('created_at')->nullable();
            
            // Optional: Add foreign key constraint for better data integrity
            $table->foreign('email')
                  ->references('email')
                  ->on('users')
                  ->onDelete('cascade');
        });
        
        // Alternative if you want to keep it simple without foreign key
        /*
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->enum('role', ['admin', 'coordinator', 'intern', 'hte'])->nullable();
            $table->timestamp('created_at')->nullable();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};