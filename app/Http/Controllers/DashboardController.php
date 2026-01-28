<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function status()
    {
        $user = Auth::user();

        // Hitung persentase XP untuk Progress Bar
        $exp_percentage = ($user->next_level_exp > 0) ? ($user->current_exp / $user->next_level_exp) * 100 : 0;

        return view('status', compact('user', 'exp_percentage'));
    }
    public function index()
    {
        // Ambil user yang sedang login
        // Auth Manual, Pake Auth::user()
        $user = Auth::user();

        // Get all list Activities (from Quest) dari database
        $activities = Activity::all();

        // Logic Gamification
        // Rumus Experience Points (XP) and next level : (Exp now / target next exp level) * 100
        if ($user->next_level_exp > 0) {
            $exp_percentage = ($user->current_exp / $user->next_level_exp) * 100;
        } else {
            $exp_percentage = 0;
        }

        // Kirim data ke view dashboard.blade.php
        return view('dashboard', compact('user', 'activities', 'exp_percentage'));
    }

    /**
     * Menampilkan Riwayat Aktivitas User
     */
    public function history()
    {
        $user = Auth::user();

        // Ambil log aktivitas milik user saat ini
        // Urutkan dari yang terbaru (latest)
        // Batasi 10 per halaman (paginate)
        $logs = \App\Models\ActivityLog::where('user_id', $user->id)
            ->with('activity') // Ambil data activity sekalian biar ringan
            ->latest()
            ->paginate(10);

        return view('history', compact('user', 'logs'));
    }

}