<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_status_to_coordinators_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coordinators', function (Blueprint $table) {
            $table->enum('status', [
                'pending documents', 
                'for validation', 
                'eligible for claim', 
                'claimed'
            ])->default('pending documents');
        });
    }

    public function down()
    {
        Schema::table('coordinators', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};