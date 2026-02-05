<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use App\Models\JadwalKkn;
use App\Models\JenisKkn;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;

class KknController extends Controller
{
    public function getJenisKkn()
    {

        $jenisKkn = JenisKkn::select('id', 'nama_jenis', 'biaya', 'is_active')->get();

        return response()->json([
            'status' => 'success',
            'data' => $jenisKkn
        ], 200);
    }

    public function validasiSyarat(Request $request)
    {

        $request->validate([
            'jenis_kkn_id' => 'required|integer'
        ]);

        $jenisKknDipilih = $request->jenis_kkn_id;

        $user = $request->user();

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data akademik atau mahasiswa tidak ditemukan'
            ], 403);
        }

        // validasi cek jadwal kkn
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

        // Mencari jadwal kkn yang dibuka
        $jadwalKkn = jadwalKkn::where('tahun_akademik_id', $tahunAktif->id)
            ->whereDate('tanggal_dibuka', '<=', now())
            ->whereDate('tanggal_ditutup', '>=', now())
            ->first();

        // jika tidak ada jadwal kkn yang sesuai
        if (!$jadwalKkn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada jadwal KKN yang tersedia'
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

        // Validasi Apakah Mahasiswa mengambil KKN di KRS
        $mataKuliahKKn = MataKuliah::where('nama_mk', 'Kuliah Kerja Nyata')
            ->first();

        if (!$mataKuliahKKn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata kuliah KKN tidak ditemukan'
            ], 422);
        }

        $sudahAdaKrs = $mahasiswa->mataKuliahs()
            ->where('mata_kuliah_id', $mataKuliahKKn->id)
            ->wherePivot('tahun_akademik_id', $tahunAktif->id)
            ->exists();

        if (!$sudahAdaKrs) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda Harus Mengambil Mata Kuliah Kuliah Kerja Nyata Terlebih Dahulu'
            ], 422);
        }

        // Validasi jenis kkn aktif atau tidak?
        $jenisKkn = JenisKkn::where('id', $jenisKknDipilih)
            ->where('is_active', true)
            ->first();

        if (!$jenisKkn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis KKN yang Anda pilih tidak valid atau tidak aktif saat ini.'
            ], 422);
        }

        // Response Akhir
        return response()->json([
            'status' => 'success',
            'is_eligible' => true,
            'message' => 'Anda memenuhi syarat untuk mendaftar KKN',
            'data' => [
                'jadwal_kkn_id' => $jadwalKkn->id,
                'jenis_kkn' => $jenisKkn->nama_jenis,
                'biaya' => $jenisKkn->biaya
            ]
        ], 200);
    }

    public function getJadwalKkn(Request $request)
    {
        $jadwalKkn = JadwalKkn::with('tahunAkademik')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $jadwalKkn
        ], 200);
    }
}
