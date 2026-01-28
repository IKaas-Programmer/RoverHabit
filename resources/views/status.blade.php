@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tighter">
                <i class="fas fa-id-card text-indigo-600 mr-2"></i> Player Information
            </h1>
            <p class="text-gray-500 italic">"The only way to level up is to keep moving forward."</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-indigo-900 rounded-3xl p-8 text-white shadow-2xl border-4 border-indigo-800 flex flex-col items-center">
                <div
                    class="w-32 h-32 rounded-full bg-indigo-700 border-4 border-yellow-400 flex items-center justify-center text-5xl font-black shadow-[0_0_20px_rgba(250,204,21,0.4)] mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-2xl font-bold mb-1">{{ $user->name }}</h2>
                <span
                    class="px-4 py-1 bg-yellow-400 text-indigo-900 rounded-full text-xs font-black uppercase tracking-widest">
                    {{ $user->role == 'admin' ? 'GRANDMASTER' : 'PLAYER' }}
                </span>

                <div class="mt-8 w-full space-y-4">
                    <div class="flex justify-between border-b border-indigo-700 pb-2">
                        <span class="text-indigo-300 text-sm">Join Date</span>
                        <span class="font-bold text-sm">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-indigo-700 pb-2">
                        <span class="text-indigo-300 text-sm">Class</span>
                        <span class="font-bold text-sm text-yellow-400">Rover-class</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Current Power</p>
                            <h3 class="text-5xl font-black text-indigo-900">LEVEL {{ $user->level }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-orange-500 uppercase tracking-widest">Active Streak</p>
                            <p class="text-2xl font-black text-gray-800"><i class="fas fa-fire"></i>
                                {{ $user->current_streak }} DAYS</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold uppercase text-gray-500">
                            <span>Experience Points</span>
                            <span>{{ $user->current_xp }} / {{ $user->next_level_xp }} XP</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                            <div class="bg-linear-to-r from-indigo-600 to-purple-600 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(79,70,229,0.5)]"
                                style="width: {{ $exp_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex items-center">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Quests Cleared</p>
                            <p class="text-lg font-black">{{ $user->activityLogs()->count() }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex items-center">
                        <div
                            class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Badges Earned</p>
                            <p class="text-lg font-black">{{ $user->badges()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
