<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegesTable extends Migration
{
    public function up()
    {
        Schema::create('colleges', function (Blueprint $table) {
            $table->id(); // college_id
            $table->string('name')->unique(); // e.g. Engineering, Architecture and Allied Discipline, Education
            $table->string('short_name')->nullable(); // optional, e.g. COE, CEAAD, CED
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('colleges');
    }
}
