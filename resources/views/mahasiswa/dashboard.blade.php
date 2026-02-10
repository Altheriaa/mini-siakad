<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MINI SIAKAD - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">MINI SIAKAD</h1>
            <div class="flex items-center space-x-6">
                <span class="text-gray-700">Selamat datang, <strong></strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md min-h-screen">
            <div class="p-6">
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <p class="text-sm text-gray-600">NIM</p>
                    <p class="text-xl font-bold text-blue-600"></p>
                </div>

                <nav class="space-y-2">
                    <a href="#" class="block px-4 py-3 bg-blue-600 text-white rounded-lg font-medium">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h2>
                <p class="text-gray-600">Selamat datang kembali! Berikut informasi akademik Anda</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Prodi</p>
                            <p class="text-3xl font-bold text-blue-600">
                                {{-- @if($userprodi)
                                {{ optional($userprodi->mahasiswa->prodi)->nama_prodi ?? '-' }}
                                @else
                                -
                                @endif --}}
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">SKS Lulus</p>
                            <p class="text-3xl font-bold text-green-600 mt-3">
                                {{-- @if($user)
                                {{ optional($user->mahasiswa)->jumlah_sks ?? '0' }}
                                @else
                                -
                                @endif --}}
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Jenis Kelamin</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-3">
                                {{-- @if($user)
                                {{ optional($user->mahasiswa)->jenis_kelamin }}
                                @else
                                -
                                @endif --}}
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-calendar-alt text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Status Mahasiswa</p>
                            <p class="text-xl font-bold text-red-600 mt-3">
                                {{-- @if($user)
                                {{ strtoupper(optional($user->mahasiswa)->status ?? '-') }}
                                @else
                                -
                                @endif --}}
                            </p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-wallet text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Matakuliah Semester Ini -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Matakuliah Semester 5</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kode MK</th>
                                    <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Matakuliah</th>
                                    <th class="px-4 py-3 text-left text-gray-700 font-semibold">SKS</th>
                                    <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">TI501</td>
                                    <td class="px-4 py-3">Pemrograman Web</td>
                                    <td class="px-4 py-3">3</td>
                                    <td class="px-4 py-3"><span
                                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">A</span>
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">TI502</td>
                                    <td class="px-4 py-3">Basis Data</td>
                                    <td class="px-4 py-3">3</td>
                                    <td class="px-4 py-3"><span
                                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">A</span>
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">TI503</td>
                                    <td class="px-4 py-3">Algoritma dan Struktur Data</td>
                                    <td class="px-4 py-3">4</td>
                                    <td class="px-4 py-3"><span
                                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">B+</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">TI504</td>
                                    <td class="px-4 py-3">Sistem Operasi</td>
                                    <td class="px-4 py-3">3</td>
                                    <td class="px-4 py-3"><span
                                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">A</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pengumuman -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Pengumuman</h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
                            <p class="text-xs text-gray-500 mb-2">20 Okt 2024</p>
                            <p class="text-sm font-semibold text-gray-800">Jadwal UAS Sudah Tersedia</p>
                            <p class="text-xs text-gray-600 mt-2">Cek jadwal UAS semester 5 di menu jadwal</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                            <p class="text-xs text-gray-500 mb-2">18 Okt 2024</p>
                            <p class="text-sm font-semibold text-gray-800">Pembayaran UKT Dibuka</p>
                            <p class="text-xs text-gray-600 mt-2">Pembayaran UKT semester 5 dibuka hingga 30 Oktober</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-600">
                            <p class="text-xs text-gray-500 mb-2">15 Okt 2024</p>
                            <p class="text-sm font-semibold text-gray-800">Nilai Ujian Tengah Semester</p>
                            <p class="text-xs text-gray-600 mt-2">Nilai UTS sudah bisa dilihat di halaman nilai</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
{{-- alernt plugin --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            customClass: {
                popup: 'glass-popup rounded-3xl shadow-blur p-6',
                title: 'font-semibold',
                icon: 'icon-custom bg-transparent'
            },
            timer: 2000
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            customClass: {
                popup: 'glass-popup rounded-3xl shadow-blur p-6',
                title: 'font-bold',
                confirmButton: 'button-confirm px-6 py-2 rounded-xl text-white',
            }
        });
    @endif
</script>

</html>