<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function apiLogin(Request $request)
    {
        // Memastikan bahwa nim dan password wajib diisi.
        $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Mengambil hanya email dan password dari input form.
        $credentials = $request->only('nim', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIM atau password salah.'
            ], 401);
        }

        // cek yang login mahasiswa ato bukan
        $user = Auth::user();
        $user->load('mahasiswa.prodi');
        $user->load('mahasiswa');
        // cuma kasi izin mahasiswa
        if ($user->role !== 'mahasiswa') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya mahasiswa yang dapat login di sistem ini.'
            ], 403);
        }

        $token = $user->createToken('kkn-payment-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'user' => [
                'id' => $user->id,
                'nim' => $user->nim,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'prodi' => [
                    'nama_prodi' => $user->mahasiswa->prodi->nama_prodi ?? 'N/A'
                ],
                'mahasiswa' => [
                    'jumlah_sks' => $user->mahasiswa->jumlah_sks ?? 'N/A',
                    'jenis_kelamin' => $user->mahasiswa->jenis_kelamin ?? 'N/A'
                ],

            ],
            'token' => $token
        ], 200);
    }
}
