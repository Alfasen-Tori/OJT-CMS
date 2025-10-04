<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dept_id')->constrained('departments', 'dept_id')->onDelete('cascade');
            $table->enum('section', ['a', 'b', 'c', 'd', 'e', 'f']);
            $table->tinyInteger('year_level')->unsigned()->between(1, 4);
            $table->string('academic_year');
            $table->enum('semester', ['1st', '2nd', 'Midyear']);
            $table->enum('status', ['pending requirements', 'ready for deployment', 'endorsed', 'processing',  'deployed'])->default('pending requirements');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interns');
    }
};