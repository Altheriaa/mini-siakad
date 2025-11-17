@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">Login</h2>
            <p class="text-gray-600 text-center mb-8">Masuk ke Sistem Mini Siakad Coy</p>

            <form class="space-y-5" action="{{ route('login.post') }}" method="POST">
                <!-- CSRF Token -->
                @csrf

                <!-- NIM/NPM -->
                <div>
                    <label for="nim" class="block text-gray-700 font-medium mb-2">NIM/NPM</label>
                    <input type="number" id="nim" name="nim" placeholder="Masukkan NIM/NPM"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <!-- Tombol Daftar -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Login
                </button>
            </form>

            <!-- Link Login -->
            <p class="text-center text-gray-600 mt-6">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:text-blue-700 transition">Daftar di
                    sini</a>
            </p>
        </div>
    </div>
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
@endsection