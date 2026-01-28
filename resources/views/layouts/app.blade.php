<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoverHabit - Gamified Journal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen overflow-hidden relative">

        <aside id="sidebar"
            class="sidebar-transition absolute md:relative z-30 inset-y-0 left-0 w-64 bg-indigo-900 text-white flex flex-col shadow-2xl transform -translate-x-full md:translate-x-0 h-full">

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto mt-2">
                @auth
                    <p class="px-2 text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-2">Main Menu
                    </p>

                    <a href="{{ route('status') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('status') ? 'bg-indigo-600 text-white shadow-lg' : 'text-indigo-200 hover:bg-indigo-800' }}">
                        <i class="fas fa-user-shield w-5 text-center mr-3"></i> Status Window
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i> Dashboard
                    </a>

                    <a href="{{ route('history') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition duration-200 {{ request()->routeIs('history') ? 'bg-indigo-600 text-white shadow-lg' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                        <i class="fas fa-scroll w-5 text-center mr-3"></i> Riwayat
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <p class="px-2 text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-2 mt-6">
                            Admin Area
                        </p>
                        <a href="{{ route('admin.activities.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition duration-200 {{ request()->routeIs('admin.*') ? 'bg-yellow-600 text-white shadow-lg' : 'text-yellow-200 hover:bg-yellow-700 hover:text-white' }}">
                            <i class="fas fa-crown w-5 text-center mr-3"></i> Kelola Quest
                        </a>
                    @endif

                    @if (Auth::user()->role == 'member' || Auth::user()->role == 'admin')
                        <p class="px-2 text-xs font-semibold text-green-400 uppercase tracking-wider mb-2 mt-6">
                            Monitoring Area
                        </p>
                        <a href="{{ route('monitor.logs') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition duration-200 {{ request()->routeIs('monitor.*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-200 hover:bg-green-800 hover:text-white' }}">
                            <i class="fas fa-glasses w-5 text-center mr-3"></i> Monitoring
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center px-4 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg">
                        <i class="fas fa-sign-in-alt w-6 text-center mr-2"></i> Login
                    </a>
                @endauth
            </nav>

            @auth
                <div class="border-t border-indigo-800 p-4 bg-indigo-950">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold border-2 border-yellow-400 shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-indigo-300">Level {{ Auth::user()->level }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded shadow transition flex items-center justify-center text-sm font-bold">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden transition-opacity duration-300"></div>

        <div class="flex-1 flex flex-col overflow-hidden w-full transition-all duration-300">

            <header class="h-16 bg-white shadow flex items-center justify-between px-4 py-4 relative z-10">
                <div class="flex items-center">

                    <button id="sidebar-toggle" onclick="toggleSidebar()"
                        class="text-gray-500 focus:outline-none hover:text-indigo-600 hover:bg-gray-100 p-2 rounded-md transition mr-3">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <a href="{{ url('/') }}"
                        class="text-xl font-bold flex items-center tracking-wider text-indigo-800 mr-6">
                        <i class="fas fa-gamepad mr-2 text-indigo-600"></i>
                        RoverHabit
                    </a>

                    <div class="hidden md:flex items-center text-gray-400 border-l border-gray-300 pl-4 h-6">
                        <h2 class="text-lg font-semibold text-gray-700">
                            @if (request()->routeIs('dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('history'))
                                Riwayat
                            @elseif(request()->routeIs('admin.*'))
                                Administrator
                            @elseif(request()->routeIs('monitor.*'))
                                Monitoring
                            @else
                                Halaman Utama
                            @endif
                        </h2>
                    </div>

                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500 font-medium hidden sm:block">{{ now()->format('d M Y') }}</span>
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow rounded relative"
                        role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            // Toggle Visibility
            sidebar.classList.toggle('-translate-x-full');

            // Overlay Logic
            if (!sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
