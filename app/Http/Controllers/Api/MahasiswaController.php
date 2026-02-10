<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MahasiswaController extends Controller
{
    public function index()
    {
        // Ambil mahasiswa berdasarkan user login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        return response()->json($mahasiswa, 200);
    }

    public function allFromSystem()
    {
        $mahasiswa = Mahasiswa::all();

        if ($mahasiswa->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data mahasiswa'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $mahasiswa,
        ], 200);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return response()->json($mahasiswa, 200);
    }

    public function syncMahasiswa()
    { 

        $users = User::with('mahasiswa.prodi.fakultas')
                ->where('role', 'mahasiswa')
                ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Sync semua mahasiswa berhasil.',
            'users' => $users,
        ], 200);
    }
}
