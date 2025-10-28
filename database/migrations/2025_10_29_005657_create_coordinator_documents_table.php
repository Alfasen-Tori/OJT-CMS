<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_coordinator_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coordinator_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coordinator_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'consolidated_moas',
                'consolidated_sics', 
                'annex_cmo104',
                'honorarium_request',
                'special_order',
                'board_resolution'
            ]);
            $table->string('file_path');
            $table->timestamps();
            
            $table->unique(['coordinator_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coordinator_documents');
    }
};