<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class ApiJenjangPendidikan extends Model
{
    protected $fillable = [
        'id',
        'kode_jenjang',
        'nama_jenjang',
        'deskripsi',
    ];

    public $timestamps = false;
}
