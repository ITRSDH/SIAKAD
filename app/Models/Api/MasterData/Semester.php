<?php

namespace App\Models\Api\MasterData;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'id_tahun_akademik',
        'nama_semester',
        'semester_akademik',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public $timestamps = false;
}
