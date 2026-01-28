@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
                <i class="fas fa-trash-restore text-red-600 mr-2"></i> Quest Trash Bin
            </h1>
            <a href="{{ route('admin.activities.index') }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-xs uppercase text-gray-400 font-black">
                        <th class="px-6 py-4">Nama Quest</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $act)
                        <tr>
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $act->name }}</td>
                            <td class="px-6 py-4 flex justify-center gap-2">
                                <form action="{{ route('admin.activities.restore', $act->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-xs font-bold hover:bg-green-200">
                                        <i class="fas fa-undo mr-1"></i> Restore
                                    </button>
                                </form>

                                <form action="{{ route('admin.activities.forceDelete', $act->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold hover:bg-red-200">
                                        <i class="fas fa-fire mr-1"></i> Burn
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-400">Tempat sampah kosong.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
