<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKkn extends Model
{
    protected $table = 'jenis_kkn';

    protected $fillable = [
        'nama_jenis',
        'biaya',
        'is_active',
    ];


}
