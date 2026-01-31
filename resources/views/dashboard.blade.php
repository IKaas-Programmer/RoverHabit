@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 text-slate-200 font-mono">
        <div
            class="border-b border-slate-800 bg-slate-900/50 p-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="border-2 border-indigo-500 p-1">
                    <div class="bg-indigo-500 text-slate-950 px-4 py-2 font-black text-xl">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tighter">{{ $user->name }}</h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Status: Active Service //
                        ID: RH-{{ $user->id }}</p>
                </div>
            </div>

            <div class="w-full md:w-96">
                <div class="flex justify-between text-[10px] font-bold mb-2 uppercase tracking-widest">
                    <span class="text-indigo-400">Level {{ $user->level }}</span>
                    <span class="text-slate-500">{{ number_format($exp_percentage, 1) }}% to next rank</span>
                </div>
                <div class="h-2 w-full bg-slate-800 rounded-none overflow-hidden border border-slate-700">
                    <div class="h-full bg-indigo-500 transition-all duration-1000 shadow-[0_0_15px_rgba(99,102,241,0.5)]"
                        style="width: {{ $exp_percentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="flex-1 p-8 border-r border-slate-800">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-4 w-1 bg-indigo-500"></div>
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">Available Quests</h2>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    @foreach ($activities as $activity)
                        @php
                            // Mapping string kategori ke class warna & glow
                            $categoryColor = match (strtolower($activity->category ?? 'main')) {
                                'main' => 'bg-yellow-400 shadow-[0_0_20px_rgba(250,204,21,0.4)]',
                                'char' => 'bg-purple-500 shadow-[0_0_20px_rgba(168,85,247,0.4)]',
                                'explor' => 'bg-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.4)]',
                                'event' => 'bg-orange-500 shadow-[0_0_20px_rgba(249,115,22,0.4)]',
                                'npc' => 'bg-blue-500 shadow-[0_0_20px_rgba(59,130,246,0.4)]',
                                default => 'bg-slate-400',
                            };

                            $textColor = match (strtolower($activity->category ?? 'main')) {
                                'main' => 'text-yellow-400',
                                'char' => 'text-purple-400',
                                'explor' => 'text-emerald-400',
                                'event' => 'text-orange-400',
                                'npc' => 'text-blue-400',
                                default => 'text-slate-400',
                            };
                        @endphp

                        <div
                            class="bg-slate-900/40 border border-slate-800 hover:border-indigo-500/50 transition-all p-8 relative group overflow-hidden">
                            <div class="absolute right-0 top-0 bottom-0 w-1 {{ $categoryColor }}"></div>

                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div
                                            class="w-12 h-12 rounded-full {{ $categoryColor }} flex-shrink- border-4 border-slate-900 group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div
                                            class="absolute -inset-1 rounded-full border border-white/5 group-hover:border-white/20 transition-colors">
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] {{ $textColor }}">
                                            {{ $activity->category ?? 'Routine' }}
                                        </span>
                                        <h3
                                            class="text-xl font-black uppercase tracking-tighter text-white group-hover:text-indigo-400 transition-colors">
                                            {{ $activity->name }}
                                        </h3>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Reward</p>
                                    <p class="text-xl font-black text-emerald-500 tracking-tighter">
                                        +{{ $activity->exp_reward }} XP</p>
                                </div>
                            </div>

                            <p
                                class="text-xs text-slate-500 leading-relaxed mb-8 h-12 overflow-hidden border-l border-slate-800/50 pl-4 group-hover:text-slate-300 transition-colors">
                                {{ Str::limit($activity->description, 95) }}
                            </p>

                            <button
                                class="w-full bg-slate-800/50 border border-slate-700 py-3 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white hover:text-slate-950 hover:border-white transition-all">
                                Execute Objective
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg:w-80 p-8 bg-slate-900/20">
                <div class="mb-10">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4">Integrity Streak</p>
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-6xl font-black text-white italic tracking-tighter">{{ $user->current_streak }}</span>
                        <span class="text-xs font-bold text-indigo-500 uppercase">Days</span>
                    </div>
                    <div class="h-1 w-full bg-slate-800 mt-4">
                        <div class="h-full bg-indigo-500" style="width: {{ ($user->current_streak / 30) * 100 }}%"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] mb-4">Quick Access</p>
                    <a href="{{ route('status') }}"
                        class="block border border-slate-800 p-4 hover:bg-white hover:text-slate-950 transition-all font-black text-[10px] uppercase tracking-widest">
                        <i class="fas fa-id-card mr-2"></i> User Status Window
                    </a>
                    <a href="{{ route('history') }}"
                        class="block border border-slate-800 p-4 hover:bg-white hover:text-slate-950 transition-all font-black text-[10px] uppercase tracking-widest">
                        <i class="fas fa-history mr-2"></i> Access Activity Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
