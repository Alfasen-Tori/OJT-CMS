<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interns_hte', function (Blueprint $table) {
            $table->string('endorsement_letter_path')->nullable()->after('deployed_at'); // Add after deployed_at for logical order
        });
    }

    public function down()
    {
        Schema::table('interns_hte', function (Blueprint $table) {
            $table->dropColumn('endorsement_letter_path');
        });
    }
};