<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityLog;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Ambil aktivitas (Quest) - Limit 5 agar dashboard tetap rapi
        $activities = Activity::latest()->take(5)->get();

        // Ambil ID quest yang sudah dikerjakan user hari ini
        $completedToday = ActivityLog::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->pluck('activity_id')
            ->toArray();

        // FILTER: Ambil Quest yang BELUM ada di daftar $completedToday
        // Dengan begini, Quest yang sudah selesai otomatis tersembunyi (Hide)
        $activities = Activity::whereNotIn('id', $completedToday)
            ->latest()
            ->take(5) // Tetap ambil 5 teratas yang tersedia
            ->get();

        // Hitung TOTAL quest yang pernah diselesaikan (LIFETIME)
        $totalCompleted = ActivityLog::where('user_id', $user->id)->count();

        return view('dashboard', [
            'user' => $user,
            'activities' => $activities,
            'completedToday' => $completedToday,
            'totalCompleted' => $totalCompleted,
            // Mengambil persentase XP langsung dari Trait
            'exp_percentage' => $user['percentage'],
        ]);
    }

    public function status()
    {
        $user = Auth::user();
        // Tambahkan info umur akun untuk Status Window yang lebih detail
        $accountAge = $user->created_at->diffInDays(now());

        // Ambil daftar badge yang dimiliki user untuk Inventory
        $inventory = $user->badges;

        return view('status', [
            'user' => $user,
            'exp_percentage' => $user['percentage'],
            'inventory' => $inventory,
            'account_age' => $accountAge
        ]);
    }

    public function history()
    {
        $user = Auth::user();

        // Gunakan Eloquent Relationship jika sudah disetting di Model User
        // Jika belum, pakai cara Anda yang sudah benar ini:
        $logs = ActivityLog::where('user_id', $user->id)
            ->with('activity')
            ->latest()
            ->paginate(10);

        return view('history', compact('user', 'logs'));
    }

    /**
     * Method untuk memproses pergantian Title
     */
    public function equipBadge(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'badge_id' => 'nullable|exists:badges,id',
        ]);

        // Proteksi: Cek apakah user benar-benar punya badge tersebut
        if ($request->badge_id && !$user->badges->contains($request->badge_id)) {
            return redirect()->back()->with('error', 'Badge tidak dimiliki.');
        }

        $user->update(['equipped_badge_id' => $request->badge_id]);

        return redirect()->back()->with('success', 'Title berhasil diganti!');
    }
}