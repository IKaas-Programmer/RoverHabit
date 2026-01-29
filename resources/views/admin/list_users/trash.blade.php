@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-red-600 uppercase tracking-tight">
                    <i class="fas fa-user-slash mr-2"></i> Banned Users (Trash)
                </h1>
                <p class="text-xs text-gray-500 font-bold mt-1 ml-1">
                    Menampilkan user yang telah di-banned.
                </p>
            </div>

            <a href="{{ route('admin.list_users.index') }}"
                class="bg-white border border-gray-200 text-gray-600 px-5 py-2 rounded-xl font-bold hover:bg-gray-50 transition shadow-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke List User
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="bg-red-50 px-6 py-3 border-b border-red-100 flex items-center gap-2">
                <i class="fas fa-info-circle text-red-500"></i>
                <span class="text-xs font-bold text-red-600">User di daftar ini tidak dapat login ke aplikasi.</span>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs uppercase text-gray-400 font-black tracking-widest">
                        <th class="px-6 py-4">Player Info</th>
                        <th class="px-6 py-4 text-center">Waktu Banned</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-red-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-black border border-red-200">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-gray-800 text-sm decoration-red-500 decoration-2 line-through">{{ $user->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex flex-col px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    <span>{{ $user->deleted_at->format('d M Y') }}</span>
                                    <span class="text-[10px] opacity-75">{{ $user->deleted_at->format('H:i') }}</span>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.list_users.restore', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Kembalikan akses user ini? Status mereka akan menjadi Aktif kembali.')">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-lg transition border border-emerald-100 shadow-sm flex items-center ml-auto"
                                        title="Pulihkan User (Un-banned)">
                                        <i class="fas fa-trash-restore mr-2"></i> Restore Account
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-check-circle text-4xl text-emerald-200 mb-3"></i>
                                    <span>Trash Bin kosong. Tidak ada user yang di-banned.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
