@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 space-y-6">

        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Quest Board</h2>
                <p class="text-sm text-gray-500">Do your Quest and Make your Progress.</p>
            </div>

            <button onclick="openCreateQuestModal()"
                class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-bold transition-all shadow-md shadow-indigo-200">
                <i class="fas fa-plus"></i>
                <span>Create Quest</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Available Quests</p>
                    <p class="text-2xl font-black text-gray-800">{{ count($activities) }}</p>
                </div>
            </div>

            <div
                class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:border-blue-300 group">
                <div class="flex items-center gap-6">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Completed</p>
                        <p class="text-xl font-black text-gray-800 tracking-tight">{{ $totalCompleted ?? 0 }} Quests</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($activities as $activity)
                        @php $style = $activity->visual; @endphp

                        <div
                            class="group bg-white border border-gray-200 rounded-xl p-5 transition-all {{ $style['border'] }} hover:shadow-xl flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <span
                                        class="text-[9px] font-bold {{ $style['bg'] }} {{ $style['text'] }} px-2 py-0.5 rounded uppercase tracking-wider flex items-center gap-1">
                                        <i class="fas {{ $style['fa'] }}"></i>
                                        {{ $activity->icon ?? 'Routine' }}
                                    </span>
                                    <span
                                        class="text-xs font-black text-emerald-600">+{{ number_format($activity->exp_reward) }}
                                        XP</span>
                                </div>

                                <h4
                                    class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors mb-2">
                                    {{ $activity->name }}
                                </h4>
                                <p class="text-xs text-gray-500 leading-relaxed mb-6 line-clamp-2">
                                    {{ $activity->description }}
                                </p>
                            </div>

                            {{-- Tombol selalu tampil 'Get The Quest' karena yang selesai sudah otomatis di-hide --}}
                            <form action="{{ route('activities.execute', $activity->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-gray-50 group-hover:bg-indigo-600 group-hover:text-white border border-gray-200 group-hover:border-indigo-600 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                    Get The Quest
                                </button>
                            </form>
                        </div>
                    @empty
                        {{-- Tampilan jika semua quest hari ini sudah selesai (Empty State) --}}
                        <div
                            class="col-span-full py-20 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-2xl">
                            <div
                                class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-check-double text-2xl"></i>
                            </div>
                            <p class="text-gray-800 font-black uppercase tracking-widest text-sm">All Quests Cleared!</p>
                            <p class="text-gray-400 text-xs mt-1 italic font-medium">Rest well, you've done everything for
                                today.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="createQuestModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" onclick="closeCreateQuestModal()">
            </div>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.activities.store') }}" method="POST" class="p-8">
                    @csrf
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">New Quest</h3>
                        <button type="button" onclick="closeCreateQuestModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Quest
                                Name</label>
                            <input type="text" name="name" required
                                class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="e.g. Morning Workout">
                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Category</label>
                                <select name="icon" class="w-full border-gray-200 rounded-lg text-sm">
                                    <option value="Routine">Routine</option>
                                    <option value="Main">Main Quest</option>
                                    <option value="Explor">Exploration</option>
                                    <option value="Char">Character</option>
                                    <option value="SideQuest">Side Quest</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">XP
                                    Reward</label>
                                <input type="number" name="exp_reward" required
                                    class="w-full border-gray-200 rounded-lg text-sm" placeholder="50">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Objective
                                Detail</label>
                            <textarea name="description" rows="3" class="w-full border-gray-200 rounded-lg text-sm"
                                placeholder="Describe what you need to do..."></textarea>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-100">
                        Create
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
