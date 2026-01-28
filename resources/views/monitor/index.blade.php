@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl mr-4">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Player</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::where('role', 'user')->count() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div
                    class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl mr-4">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Aktivitas Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ \App\Models\ActivityLog::whereDate('created_at', today())->count() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div
                    class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xl mr-4">
                    <i class="fas fa-fire"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Avg. Streak</p>
                    <p class="text-2xl font-bold text-gray-800">{{ round(\App\Models\User::avg('current_streak'), 1) }} Hari
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <i class="fas fa-stream mr-2 text-indigo-500"></i> Pantauan Aktivitas Real-time
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase text-gray-400 font-semibold bg-gray-50/50">
                                    <th class="px-6 py-3">Waktu</th>
                                    <th class="px-6 py-3">Player</th>
                                    <th class="px-6 py-3">Quest</th>
                                    <th class="px-6 py-3">XP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $log->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs mr-2">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-gray-700">{{ $log->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $log->activity->icon }} {{ $log->activity->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-xs font-bold text-green-600">+{{ $log->activity->exp_reward }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada
                                            aktivitas terekam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-50">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-yellow-50/50">
                        <h3 class="font-bold text-yellow-700 flex items-center">
                            <i class="fas fa-trophy mr-2"></i> Top 5 Player
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($topUsers as $index => $player)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center">
                                    <span
                                        class="text-lg font-black {{ $index == 0 ? 'text-yellow-500' : 'text-gray-300' }} mr-4">#{{ $index + 1 }}</span>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $player->name }}</p>
                                        <p class="text-xs text-orange-500 font-medium"><i class="fas fa-fire-alt"></i>
                                            {{ $player->current_streak }} Hari</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded">Lvl
                                        {{ $player->level }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
