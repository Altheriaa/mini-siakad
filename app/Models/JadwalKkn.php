<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalKkn extends Model
{
    protected $table = 'jadwal_kkn';

    protected $fillable = [
        'prodi_id',
        'tahun_akademik_id',
        'tanggal_dibuka',
        'tanggal_ditutup',
        'jenis_kkn_id',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    // public function jenisKkn()
    // {
    //     return $this->belongsTo(JenisKkn::class, 'jenis_kkn_id');
    // }

    // jika kolom bertipe datetime/date
    protected $dates = ['tanggal_dibuka', 'tanggal_ditutup'];

    // tambahkan agar attribute tersedia di JSON
    protected $appends = ['status_pendaftaran'];

    public function getStatusPendaftaranAttribute(): bool
    {
        $now = Carbon::now();

        if (! $this->tanggal_dibuka || ! $this->tanggal_ditutup) {
            return false;
        }

        return $now->between(Carbon::parse($this->tanggal_dibuka), Carbon::parse($this->tanggal_ditutup));
    }

    /**
     * Scope untuk mengambil hanya jadwal yang sedang aktif pendaftarannya.
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateTimeString();
        return $query->where('tanggal_dibuka', '<=', $now)
            ->where('tanggal_ditutup', '>=', $now);
    }
}
