@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center h-[80vh]">
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">🚀 Login Area</h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>

                    <div class="relative">
                        <input type="password" name="password" id="login-password"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 pr-10"
                            required>

                        <span
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 hover:text-indigo-600"
                            onclick="togglePassword('login-password', 'icon-login')">
                            <i id="icon-login" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 transform hover:-translate-y-0.5 shadow-md">
                    Masuk Sekarang
                </button>
            </form>

            <p class="text-center mt-4 text-sm text-gray-600">
                Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Daftar
                    di sini</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Ganti icon jadi mata dicoret
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Balikin jadi mata biasa
            }
        }
    </script>
@endsection
