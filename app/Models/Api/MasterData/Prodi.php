<?php

namespace App\Models\Api\MasterData;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'id',
        'kode_prodi',
        'nama_prodi',
        'id_jenjang_pendidikan',
        'akreditasi',
        'tahun_berdiri',
        'kuota',
        'gelar_lulusan',
    ];

    public $timestamps = false;
}
