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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->string('table_name'); // e.g., 'users', 'interns', 'htes'
            $table->string('record_id'); // ID of the affected record
            $table->string('action'); // 'created', 'updated', 'deleted', 'role_assigned'
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->text('changes')->nullable(); // Human-readable change description
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Who performed the action
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // 'admin', 'coordinator', 'intern', 'hte'
            
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes for better performance
            $table->index(['table_name', 'record_id']);
            $table->index('action');
            $table->index('user_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};