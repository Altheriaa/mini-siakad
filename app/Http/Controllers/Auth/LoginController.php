<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;

class LoginController extends Controller
{

    public function storeExcel(Request $request)
    {
        // Cek dulu apakah filenya benar-benar ada di request
        if (!$request->hasFile('file_excel')) {
            return back()->with('error', 'File tidak terdeteksi!');
        }

        set_time_limit(0);

        // Gunakan nama yang sama dengan di tag <input name="...">
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file_excel');
        
        // Panggil class import-nya
        Excel::import(new MahasiswaImport, $file);

        return back()->with('success', 'Import Berhasil!');
    }

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/mini-siakad');
            }
            if ($user->role === 'mahasiswa') {
                return redirect()->route('mahasiswa.dashboard');
            }
            // role tidak dikenal — logout dan tunjukkan login
            Auth::logout();
            return redirect('/');
        }
        return view('login');
    }

    public function login(Request $request)
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session untuk keamanan
            $user = Auth::user();

            if ($user->role === 'mahasiswa') {
                return redirect()->route('mahasiswa.dashboard')->with('success', 'Login berhasil');
                // return back()->withErrors(['success' => 'Login berhasil'])->withInput();
            } else if ($user->role === 'admin') {
                return back()->withErrors(['Silahkan Login di Admin Panel'])->withInput();
            }
            return back()->withErrors(['nim' => 'Nim atau Password salah'])->withInput();

            if (Auth::user()->role === 'admin') {
                return back()->withErrors(['Silahkan Login di Admin Panel'])->withInput();
            }
        }
        return back()->withErrors(['nim' => 'Nim atau Password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logout berhasil');
    }

    // login admin to filament panel
    public function indexAdmin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/mini-siakad');
            }
            if ($user->role === 'mahasiswa') {
                return redirect()->route('mahasiswa.dashboard');
            }
            // role tidak dikenal — logout dan tunjukkan login
            Auth::logout();
            return redirect('/');
        }
        return view('loginAdmin');
    }

    public function loginAdmin(Request $request)
    {
        // Memastikan bahwa nim dan password wajib diisi.
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Mengambil hanya email dan password dari input form.
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session untuk keamanan
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/mini-siakad')->with('success', 'Login berhasil!');
                // return back()->withErrors(['success' => 'Login berhasil'])->withInput();
            }
            return back()->withErrors(['email' => 'Email atau Password salah'])->withInput();

            if (Auth::user()->role === 'mahasiswa') {
                return back()->withErrors(['Silahkan Login di Mahasiswa Panel'])->withInput();
            }
        }
        return back()->withErrors(['email' => 'Email atau Password salah'])->withInput();
    }
}
