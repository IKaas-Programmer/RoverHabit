<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoverHabit - Gamified Journal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-indigo-600 p-4 shadow-lg text-white">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold flex items-center">
                <i class="fas fa-gamepad mr-2"></i> RoverHabit
            </a>

            <div>
                @auth
                    <span class="mr-4">Halo, {{ Auth::user()->name }} (Lvl. {{ Auth::user()->level }})</span>

                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-200 mr-4">Dashboard</a>
                    <a href="{{ route('admin.activities.index') }}" class="hover:text-indigo-200 mr-4">Admin</a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-200 mr-4">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-white text-indigo-600 px-3 py-1 rounded hover:bg-gray-100 transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 px-4">

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </div>

</body>

</html>
