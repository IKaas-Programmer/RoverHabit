@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
                    <i class="fas fa-tasks text-indigo-600 mr-2"></i> Kelola Quest
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.activities.create') }}"
                    class="bg-cyan-600 text-white px-5 py-2 rounded-xl font-bold hover:bg-cyan-700 transition shadow-lg shadow-cyan-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Create New Quest
                </a>

                <a href="{{ route('admin.activities.trash') }}"
                    class="text-xs font-bold text-red-500 hover:text-red-700 transition flex items-center">
                    <i class="fas fa-trash-alt mr-1"></i> Lihat Trash Bin
                    ({{ \App\Models\Activity::onlyTrashed()->count() }})
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs uppercase text-gray-400 font-black tracking-widest">
                        <th class="px-6 py-4 text-left">Quest Info</th>
                        <th class="px-6 py-4 text-center">Reward</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $act)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">

                                    <div class="shrink-0">
                                        @php
                                            // Tentukan Warna Berdasarkan Kategori
                                            $colorClass = match ($act->icon) {
                                                'main'
                                                    => 'bg-yellow-400 border-yellow-200 shadow-[0_0_15px_rgba(250,204,21,0.6)]',
                                                'character'
                                                    => 'bg-purple-500 border-purple-300 shadow-[0_0_15px_rgba(168,85,247,0.6)]',
                                                'exploration'
                                                    => 'bg-emerald-500 border-emerald-300 shadow-[0_0_15px_rgba(16,185,129,0.6)]',
                                                'event'
                                                    => 'bg-orange-500 border-orange-300 shadow-[0_0_15px_rgba(249,115,22,0.6)]',
                                                'side'
                                                    => 'bg-blue-500 border-blue-300 shadow-[0_0_15px_rgba(59,130,246,0.6)]',
                                                default => 'bg-gray-300 border-gray-200', // Warna untuk data lama
                                            };

                                            $tooltip = match ($act->icon) {
                                                'main' => 'Quest Utama',
                                                'character' => 'Quest Karakter',
                                                'exploration' => 'Quest Explorasi',
                                                'event' => 'Event Terbatas',
                                                'side' => 'Quest NPC',
                                                default => 'Unknown',
                                            };
                                        @endphp

                                        <div class="relative flex items-center justify-center w-8 h-8"
                                            title="{{ $tooltip }}">
                                            <span
                                                class="absolute inline-flex h-full w-full rounded-full opacity-30 animate-ping {{ str_replace('border-', 'bg-', explode(' ', $colorClass)[0]) }}"></span>
                                            <div class="relative w-4 h-4 rounded-full border-2 {{ $colorClass }}"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-black text-gray-800 text-lg leading-tight uppercase tracking-tight">
                                            {{ $act->name }}
                                        </span>
                                        <span class="text-xs text-gray-400 italic max-w-xs truncate">
                                            {{ $act->description ?? 'No description available.' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-black border border-yellow-200">
                                    +{{ $act->exp_reward }} XP
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('admin.activities.edit', $act->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm"
                                        title="Edit Quest">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.activities.destroy', $act->id) }}" method="POST"
                                        onsubmit="return confirm('Pindahkan quest ini ke Trash Bin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm"
                                            title="Delete Quest">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">Belum ada Quest yang
                                diciptakan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
