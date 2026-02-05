<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Prodi;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nama',
        'jumlah_sks',
        'status',
        'jenis_kelamin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'krs', 'mahasiswa_id', 'mata_kuliah_id')
            ->using(Krs::class)
            ->withPivot(['tahun_akademik_id'])
            ->withTimestamps();
    }
}
