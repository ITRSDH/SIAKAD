<?php

namespace App\Models\Api\MasterData;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $fillable = [
        'id',
        'tahun_akademik',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_aktif',
    ];

    public $timestamps = false;
}
