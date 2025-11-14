<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use App\Models\JadwalKkn;
use App\Models\JenisKkn;
use App\Models\Mahasiswa;

class KknController extends Controller
{
    public function getJenisKkn()
    {

        $jenisKkn = JenisKkn::where('is_active', true)->select('id', 'nama_jenis', 'biaya')->get();

        return response()->json([
            'status' => 'success',
            'data' => $jenisKkn
        ], 200);
    }

    public function validasiSyarat(Request $request)
    {

        // 0. Validasi input (terima ID dari KKN Payment)
        $request->validate([
            'jenis_kkn_id' => 'required|integer'
        ]);

        $jenisKknDipilih = $request->jenis_kkn_id;

        // ambil yang sedang login berdasarkan token
        $user = $request->user();

        // Ambil data akademik dari relasi 'mahasiswa'
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data akademik atau mahasiswa tidak ditemukan'
            ], 403);
        }

        // 1. validasi cek jadwal kkn
        // dapatkan tahun akademik yang sedang aktif
        $tahunAktif = TahunAkademik::where('aktif', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$tahunAktif) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada tahum akademik yang aktif'
            ], 422);
        }

        // Mencari jadwal kkn yang sesuai
        $jadwalKkn = jadwalKkn::where('prodi_id', $mahasiswa->prodi_id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            // ->where('status_pendaftaran', true)
            ->whereDate('tanggal_dibuka', '<=', now()) // Pendaftaran sudah dimulai
            ->whereDate('tanggal_ditutup', '>=', now()) // Pendaftaran belum ditutup
            ->first();

        // jika tidak ada jadwal kkn yang sesuai
        if (!$jadwalKkn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada jadwal KKN yang sesuai untuk program studi ini'
            ], 422);
        }

        // validasi syarat minimal sks 
        $sksMahasiswa = $mahasiswa->jumlah_sks;
        $sksMinimal = 110;

        if ($sksMahasiswa < $sksMinimal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah SKS tidak mencukupi. Minimal SKS untuk mendaftar KKN adalah ' . $sksMinimal . ' SKS.'
            ], 422);
        }

        $jenisKkn = JenisKkn::where('id', $jenisKknDipilih)
            ->where('is_active', true)
            ->first();

        if (!$jenisKkn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis KKN yang Anda pilih tidak valid atau tidak aktif saat ini.'
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'is_eligible' => true,
            'message' => 'Anda memenuhi syarat untuk mendaftar KKN',
            'data' => [
                'jadwal_kkn_id' => $jadwalKkn->id,
                'jenis_kkn' => $jenisKkn->nama_jenis,
                'biaya' => $jenisKkn->biaya // <-- PENTING: Kirim biayanya
            ]
        ], 200);
    }

    public function validasiSyaratAdmin(Request $request)
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->first();
        if (!$tahunAktif) {
            return response()->json(['message' => 'Tidak ada tahun akademik yang aktif'], 422);
        }

        // 2. Dapatkan SEMUA prodi_id yang pendaftarannya sedang dibuka
        $prodiIdsAktif = JadwalKkn::where('tahun_akademik_id', $tahunAktif->id)
            // ->where('status_pendaftaran', true)
            ->whereDate('tanggal_dibuka', '<=', now())
            ->whereDate('tanggal_ditutup', '>=', now())
            ->pluck('prodi_id') // Ambil hanya kolom prodi_id
            ->unique(); // Pastikan unik

        if ($prodiIdsAktif->isEmpty()) {
            return response()->json(['message' => 'Tidak ada jadwal KKN yang dibuka saat ini.'], 404);
        }

        // 3. Tentukan SKS minimal
        $sksMinimal = 110;

        // 4. Cari SEMUA mahasiswa yang memenuhi KEDUA syarat:
        //    a. SKS mereka cukup
        //    b. prodi_id mereka ada di dalam daftar prodi yang aktif
        $mahasiswaEligible = Mahasiswa::with('prodi') // 'with' agar efisien
            ->where('jumlah_sks', '>=', $sksMinimal)
            ->whereIn('prodi_id', $prodiIdsAktif) // Cek jika prodi_id ada di daftar
            ->get();

        // 5. Kembalikan datanya
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil ' . $mahasiswaEligible->count() . ' mahasiswa eligible.',
            'data' => $mahasiswaEligible
        ], 200);
    }

    public function getJadwalKkn(Request $request)
    {

        $jadwalKkn = JadwalKkn::with('prodi', 'tahunAkademik')
            ->whereDate('tanggal_dibuka', '<=', now())
            ->whereDate('tanggal_ditutup', '>=', now())
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $jadwalKkn
        ], 200);
    }
}
