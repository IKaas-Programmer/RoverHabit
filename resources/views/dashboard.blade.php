@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 text-center border-t-4 border-indigo-500">
        <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
        <div class="inline-block bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-bold mt-2">
            Level {{ $user->level }}
        </div>

        <div class="max-w-lg mx-auto mt-4">
            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                <span>Current: {{ $user->current_exp }} EXP</span>
                <span>Next Lvl: {{ $user->next_level_exp }} EXP</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-linear-to-r from-blue-400 to-indigo-600 h-4 rounded-full transition-all duration-500 ease-out"
                    style="width: {{ $exp_percentage }}%"></div>
            </div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
        <i class="fas fa-scroll mr-2 text-indigo-500"></i> Daily Quests
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($activities as $activity)
            <div
                class="bg-white rounded-lg shadow hover:shadow-xl transform hover:-translate-y-1 transition duration-300 overflow-hidden border border-gray-100">
                <div class="p-6 text-center">
                    <div class="text-5xl mb-4">{{ $activity->icon }}</div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $activity->name }}</h4>
                    <p class="text-green-600 font-bold text-lg">+{{ $activity->exp_reward }} EXP</p>
                    <p class="text-gray-500 text-sm mt-2 h-10">{{ Str::limit($activity->description, 50) }}</p>

                    <button
                        class="mt-4 w-full bg-indigo-100 text-indigo-700 font-bold py-2 rounded hover:bg-indigo-600 hover:text-white transition">
                        Selesai!
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endsection
