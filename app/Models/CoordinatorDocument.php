<?php
// app/Models/CoordinatorDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinator_id',
        'type',
        'file_path'
    ];

    public static function typeLabels()
    {
        return [
            'consolidated_moas' => 'Consolidated Notarized MOAs',
            'consolidated_sics' => 'Consolidated Notarized SICs',
            'annex_c' => 'ANNEX C CMO104 Series of 2017',
            'annex_d' => 'ANNEX D CMO104 Series of 2017',
            'honorarium_request' => 'Honorarium Request',
            'special_order' => 'Special Order',
            'board_resolution' => 'Board Resolution'
        ];
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }
}