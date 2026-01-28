@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
                <i class="fas fa-users-cog text-indigo-600 mr-2"></i> Data List User
            </h1>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.list_users.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Cari berdasarkan nama atau email...">
                </div>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">
                    Search
                </button>
                @if ($search)
                    <a href="{{ route('admin.list_users.index') }}"
                        class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs uppercase text-gray-400 font-black tracking-widest">
                        <th class="px-6 py-4 text-center">ID</th>
                        <th class="px-6 py-4">Identity</th>
                        <th class="px-6 py-4">RPG Status</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-center font-mono text-gray-400 text-sm">#{{ $u->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-indigo-600">LVL. {{ $u->level }}</span>
                                    <div class="w-24 bg-gray-200 h-1.5 rounded-full mt-1">
                                        @php $pct = ($u->next_level_xp > 0) ? ($u->current_xp / $u->next_level_xp) * 100 : 0; @endphp
                                        <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $pct }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($u->role == 'admin')
                                    <span
                                        class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-black uppercase">Admin</span>
                                @elseif($u->role == 'member')
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase">Pengawas</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase">Player</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                Data user tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $users->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>
@endsection
