<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    /**
     * Helper untuk menghitung data gamifikasi agar tidak nulis berulang-ulang
     */
    private function getGamificationData($user)
    {
        // 1. Hitung XP Progress
        $percentage = ($user->next_level_exp > 0)
            ? ($user->current_exp / $user->next_level_exp) * 100
            : 0;

        // 2. Tentukan Rank / Title (Resonator Tier)
        $rank = 'Novice Class';

        if ($user->level >= 80)
            $rank = 'Master Class';
        elseif ($user->level >= 60)
            $rank = 'Senior Class';
        elseif ($user->level >= 25)
            $rank = 'Elite Class';
        elseif ($user->level >= 10)
            $rank = 'Adept Class';

        return [
            'percentage' => $percentage,
            'rank' => $rank
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $gami = $this->getGamificationData($user);

        // Ambil aktivitas (Quest) - Limit 5 agar dashboard tetap rapi
        $activities = Activity::latest()->take(5)->get();

        return view('dashboard', [
            'user' => $user,
            'activities' => $activities,
            'exp_percentage' => $gami['percentage'],
            'rank' => $gami['rank']
        ]);
    }

    public function status()
    {
        $user = Auth::user();
        $gami = $this->getGamificationData($user);

        // Tambahkan info umur akun untuk Status Window yang lebih detail
        $accountAge = $user->created_at->diffInDays(now());

        return view('status', [
            'user' => $user,
            'exp_percentage' => $gami['percentage'],
            'rank' => $gami['rank'],
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
}