<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    // Save Activity Log ketika user mengerjakan aktivitas
    public function store(Request $request)
    {
        // 1. Validasi: Pastikan activity_id yang dikirim itu ada di database
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
        ]);

        $user = Auth::user();
        $activity = Activity::findOrFail($request->activity_id);

        // 2. Simpan ke Tabel ActivityLog (Catat Sejarah)
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'note' => 'Berhasil menyelesaikan quest: ' . $activity->name
        ]);

        // 3. Update XP & Level (Memanggil fungsi yang kita buat di Model User)
        $user->addXp($activity->exp_reward);

        // 4. Update Streak (Memanggil fungsi streak di Model User)
        $user->updateStreak();

        // 5. Cek Badge (Pencapaian)
        $user->checkForBadges();

        // 6. Kembali ke Dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', "Quest Selesai! Kamu dapat +{$activity->exp_reward} XP!");
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang boleh menghapus jejak digital!');
        }

        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Riwayat aktivitas berhasil dihapus.');
    }

}