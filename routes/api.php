<?php

use App\Http\Controllers\Api\KknController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// login api
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// data login
Route::post('/auth/login', [LoginController::class, 'apiLogin']);

// data mahasiswa

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);

    // syarat kkn 
    Route::post('/kkn/syarat', [KknController::class, 'validasiSyarat']);
});

Route::middleware('check.system.key')->group(function () {
    Route::get('/mahasiswa/admin', [MahasiswaController::class, 'allFromSystem']);
    Route::get('/mahasiswa/admin/{id}', [MahasiswaController::class, 'show']);
    Route::get('/kkn/jenis', [KknController::class, 'getJenisKkn']);
    Route::get('/kkn/syarat/admin', [KknController::class, 'validasiSyaratAdmin']);
    Route::get('/jadwal-kkn', [KknController::class, 'getJadwalKkn']);
});

// Route::get('/check-key', function () {
//     return env('SYSTEM_API_KEY');
// });

// Route::get('/test-mahasiswa', function () {
//     return DB::table('mahasiswa')->get();
// });
