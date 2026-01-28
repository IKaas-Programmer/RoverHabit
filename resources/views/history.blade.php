@extends('layouts.app')

@section('content')
    <div class="flex flex-col">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📜 Riwayat Perjalanan</h1>
            <p class="text-gray-500">Jejak langkah produktivitasmu tercatat di sini.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <span class="font-bold text-gray-700">Log Aktivitas</span>
                <span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded">
                    Total: {{ $logs->total() }}
                </span>
            </div>

            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50">
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Quest / Aktivitas</th>
                        <th class="px-6 py-3 text-center">Reward</th>
                        <th class="px-6 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-700">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">{{ $log->activity->icon }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $log->activity->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    +{{ $log->activity->exp_reward }} XP
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-400 italic">
                                {{ $log->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-ghost text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada riwayat aktivitas.</p>
                                    <a href="{{ route('dashboard') }}"
                                        class="text-indigo-600 hover:underline mt-2 text-sm">Mulai kerjakan quest!</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($logs->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
