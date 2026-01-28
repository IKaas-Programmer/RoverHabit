@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[80vh]">
        <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8 border border-gray-100">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">📝 Daftar Akun Baru</h2>
                <p class="text-gray-500 text-sm">Mulai petualangan habit kamu hari ini!</p>
            </div>

            <form action="{{ route('register.process') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                        placeholder="Jagoan Neon" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                        placeholder="nama@email.com" required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="reg-password"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 pr-10 @error('password') border-red-500 @enderror"
                            required>

                        <span
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 hover:text-indigo-600"
                            onclick="togglePassword('reg-password', 'icon-pass')">
                            <i id="icon-pass" class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ulangi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="reg-confirm"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 pr-10"
                            required>

                        <span
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 hover:text-indigo-600"
                            onclick="togglePassword('reg-confirm', 'icon-confirm')">
                            <i id="icon-confirm" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 shadow-md transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>
            </form>

            <div class="text-center mt-6 border-t pt-4">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
