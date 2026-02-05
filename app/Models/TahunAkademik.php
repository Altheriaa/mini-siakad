<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
    ];

    public function krs()
    {
        return $this->hasMany(Krs::class, 'tahun_akademik_id');
    }
}
