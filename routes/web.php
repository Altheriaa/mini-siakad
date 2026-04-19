<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isMahasiswa;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');

Route::get('/login/admin', [LoginController::class, 'indexAdmin'])->name('login.admin')->middleware('guest');
Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin.post')->middleware('guest');
Route::post('/import', [LoginController::class, 'storeExcel'])->name('login.import');

Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register/create', [RegisterController::class, 'store'])->name('register.post')->middleware('guest');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('mahasiswa.dashboard');
});

Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
