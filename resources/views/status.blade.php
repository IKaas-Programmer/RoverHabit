@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="flex flex-col md:flex-row">

                <div class="md:w-1/3 p-8 border-r border-gray-100 flex flex-col">

                    <div class="flex flex-row items-center mb-6">

                        <div class="mr-6 shrink-0 relative group">
                            <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <label for="avatar-input" class="cursor-pointer block">
                                    <div
                                        class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-white shadow-sm overflow-hidden ring-2 ring-indigo-500/20 relative">

                                        @if ($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}?v={{ time() }}"
                                                alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl font-bold text-indigo-600">
                                                {{ substr($user->name, 0, 2) }}
                                            </span>
                                        @endif

                                        <div
                                            class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <i class="fas fa-camera text-white text-xl drop-shadow-md"></i>
                                        </div>
                                    </div>

                                    <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
                                </label>
                                @error('avatar')
                                    <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p>
                                @enderror

                                @if (session('success'))
                                    <p class="text-green-500 text-xs mt-2 text-center">{{ session('success') }}</p>
                                @endif
                            </form>
                        </div>

                        <div class="text-left w-full ">

                            <div class="w-full max-w-md">

                                <div onclick="openModal()"
                                    class="flex items-center justify-between border-b-2 border-gray-300 pb-2 cursor-pointer group hover:border-indigo-500 transition-colors">

                                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight select-none">
                                        {{ $user->name }}
                                    </h2>

                                    <button class="text-gray-400 group-hover:text-indigo-600 transition"
                                        title="Edit Username">
                                        <i class="fas fa-pencil-alt text-lg"></i>
                                    </button>
                                </div>

                            </div>

                            <div class="w-full max-w-50 mt-2">
                                <div onclick="openInventoryModal()"
                                    class="flex items-center justify-between border-b border-dashed border-gray-300 pb-1 cursor-pointer group hover:border-indigo-500 transition-colors">

                                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wide select-none">
                                        {{ $user->rank_label }}
                                    </p>

                                    <button class="text-gray-400 group-hover:text-indigo-600 transition ml-2"
                                        title="Change Title">
                                        <i class="fas fa-book text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                            <p class="text-[14px] text-gray-400 uppercase font-semibold tracking-widest mt-1">
                                ID: {{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full border-t border-gray-50 pt-6">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">Level</span>
                                <p class="text-2xl font-bold text-gray-900 leading-none">{{ $user->level }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Progress</span>
                                <span class="text-sm font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">
                                    {{ number_format($exp_percentage, 1) }}%
                                </span>
                            </div>
                        </div>

                        <div class="h-4 w-full bg-gray-50 rounded-full overflow-hidden border-2 border-gray-200 p-0.5">
                            <div class="h-full bg-linear-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-1000 shadow-[0_0_4px_rgba(99,102,241,0.3)]"
                                style="width: {{ $exp_percentage }}%"></div>
                        </div>

                        <div class="flex justify-between mt-3">
                            <div class="text-left">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">
                                    Current XP</p>
                                <p class="text-xs font-bold text-gray-700">{{ number_format($user->current_xp) }}</p>
                            </div>

                            @php
                                // Menghitung sisa XP yang dibutuhkan untuk Level Up
                                $remainingXp = $user->next_level_xp - $user->current_xp;
                            @endphp

                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Target
                                    XP</p>
                                <p class="text-xs font-bold text-gray-700">{{ number_format($user->next_level_xp) }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex-1 p-8 bg-gray-50/50">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 ">Status Player
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Traveler Journey
                            </p>
                            @php
                                $diff = $user->created_at->diff(now());
                                $totalHours = number_format($user->created_at->diffInHours(now()));
                            @endphp
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-gray-800">{{ $diff->y }}</span><span
                                    class="text-xs font-bold text-gray-400 mr-2">Y</span>
                                <span class="text-3xl font-black text-gray-800">{{ $diff->m }}</span><span
                                    class="text-xs font-bold text-gray-400 mr-2">M</span>
                                <span class="text-3xl font-black text-gray-800">{{ $diff->d }}</span><span
                                    class="text-xs font-bold text-gray-400">D</span>
                            </div>
                            <p class="mt-4 text-[11px] text-gray-500 font-medium">
                                Total Time: <span class="text-indigo-600 font-bold">{{ $totalHours }} Hours</span>
                            </p>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Integrity Streak
                            </p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-5xl font-black text-orange-500">{{ $user->current_streak }}</span>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Days</span>
                            </div>
                            <p class="mt-4 text-[11px] text-gray-500 font-medium italic">Keep resonating daily!</p>
                        </div>
                    </div>

                    <div class="mt-8 bg-white rounded-2xl border-2 border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200 flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Activity
                                Accumulation</span>
                            <i class="fas fa-chart-column text-gray-400 text-xs"></i>
                        </div>

                        <div class="p-8">
                            <div class="relative h-40 flex items-end justify-between gap-3 border-b-2 border-gray-100 pb-1">
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none py-2">
                                    <div class="border-b border-gray-100 w-full h-0"></div>
                                    <div class="border-b border-gray-100 w-full h-0"></div>
                                </div>

                                @foreach ($chartData as $label => $count)
                                    @php
                                        $barHeight = ($count / $maxVal) * 100;
                                    @endphp

                                    <div class="flex-1 flex flex-col items-center group relative z-10">
                                        <div
                                            class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-all duration-300 bg-gray-900 text-white text-[10px] font-black px-2 py-1 rounded shadow-xl">
                                            {{ number_format($count) }} Quests
                                        </div>

                                        <div class="w-full max-w-11.25 bg-indigo-500 rounded-t-lg transition-all duration-1000 ease-out hover:bg-purple-600 cursor-pointer shadow-[0_-4px_10px_rgba(99,102,241,0.1)]"
                                            style="height: {{ $barHeight }}%; min-height: 5px;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between mt-4">
                                <div class="flex-1 text-center">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">1 Week</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">3 Months</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">6 Months</p>
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">1 Year</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-white rounded-2xl border-2 border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200 flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Attribute
                                Resonance</span>
                            <i class="fas fa-hexagon text-gray-400 text-xs"></i>
                        </div>



                        <div class="p-8 flex flex-col items-center">

                            <div class="mb-6 text-center">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Current
                                    Class</span>
                                <h2
                                    class="text-2xl font-black text-indigo-600 uppercase tracking-tighter mt-1 drop-shadow-sm">
                                    {{ $archetype ?? 'Novice' }}
                                </h2>

                                {{-- Flavor Text Optional --}}
                                @if (isset($archetype))
                                    @if ($archetype == 'Wind Walker')
                                        <p class="text-[10px] text-gray-400 mt-1 italic font-medium">"Speed is your
                                            ultimate weapon."</p>
                                    @elseif($archetype == 'Technomancer')
                                        <p class="text-[10px] text-gray-400 mt-1 italic font-medium">"Reality bends to your
                                            logic."</p>
                                    @elseif($archetype == 'Warlord')
                                        <p class="text-[10px] text-gray-400 mt-1 italic font-medium">"Strength conquers all
                                            obstacles."</p>
                                    @endif
                                @endif
                            </div>

                            <div class="w-full max-w-xs aspect-square relative mx-auto">
                                <canvas id="hexStatsChart" data-labels="{{ json_encode($radarLabels) }}"
                                    data-values="{{ json_encode($radarData) }}">
                                </canvas>
                            </div>

                            <div class="mt-8 grid grid-cols-3 gap-3 w-full">
                                @foreach ($radarLabels as $index => $label)
                                    <div
                                        class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100 group hover:border-indigo-300 transition-colors">
                                        <p
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mb-1 group-hover:text-indigo-500">
                                            {{ $label }}
                                        </p>
                                        <p class="text-sm font-black text-gray-800">
                                            {{ number_format($radarData[$index] ?? 0) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editNameModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
        </div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">

                <form action="{{ route('profile.update_name') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-user-edit text-indigo-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Edit Username
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Please enter your new display name below.</p>

                                    <input type="text" name="name" value="{{ $user->name }}"
                                        id="modalInputName"
                                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-bold "
                                        placeholder="Enter new username" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="cropModal" class="hidden fixed inset-0 z-60 overflow-y-auto" aria-labelledby="crop-modal-title"
        role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4" id="crop-modal-title">Adjust
                                Avatar</h3>

                            <div class="img-container w-full h-100 bg-gray-100 rounded-lg overflow-hidden">
                                <img id="image-to-crop" src="" alt="Picture to crop" class="max-w-full">
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Drag to move, scroll to zoom.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" id="crop-and-save-btn"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                        Crop & Save
                    </button>
                    <button type="button" onclick="closeCropModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div id="inventoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="inventory-modal-title"
        role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
            onclick="closeInventoryModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-medal text-yellow-500"></i> Change Title
                    </h3>

                    <p class="text-sm text-gray-500 mb-4">Select a title to display on your profile.</p>

                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">

                        <form action="{{ route('profile.equip_badge') }}" method="POST">
                            @csrf
                            <input type="hidden" name="badge_id" value=""> <button type="submit"
                                class="w-full flex items-center justify-between p-3 rounded-lg border {{ is_null($user->equipped_badge_id) ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-gray-200 hover:bg-gray-50' }} transition">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-bold text-gray-800">Default Rank</p>
                                        <p class="text-xs text-gray-500">Based on your Level</p>
                                    </div>
                                </div>
                                @if (is_null($user->equipped_badge_id))
                                    <span class="text-xs font-bold text-indigo-600"><i class="fas fa-check-circle"></i>
                                        Equipped</span>
                                @endif
                            </button>
                        </form>

                        @forelse($inventory as $badge)
                            <form action="{{ route('profile.equip_badge') }}" method="POST">
                                @csrf
                                <input type="hidden" name="badge_id" value="{{ $badge->id }}">
                                <button type="submit"
                                    class="w-full flex items-center justify-between p-3 rounded-lg border {{ $user->equipped_badge_id == $badge->id ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-gray-200 hover:bg-gray-50' }} transition">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 border border-yellow-200">
                                            @if ($badge->image_path)
                                                <img src="{{ $badge->image_path }}"
                                                    class="w-full h-full rounded-full object-cover">
                                            @else
                                                <i class="fas fa-certificate"></i>
                                            @endif
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-bold text-gray-800">{{ $badge->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $badge->description }}</p>
                                        </div>
                                    </div>
                                    @if ($user->equipped_badge_id == $badge->id)
                                        <span class="text-xs font-bold text-indigo-600"><i
                                                class="fas fa-check-circle"></i> Equipped</span>
                                    @endif
                                </button>
                            </form>
                        @empty
                            <p class="text-center text-xs text-gray-400 py-4 italic">
                                You haven't unlocked any badges yet. <br> Complete quests to earn them!
                            </p>
                        @endforelse

                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeInventoryModal()"
                        class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Close</button>
                </div>
            </div>
        </div>>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endsection
