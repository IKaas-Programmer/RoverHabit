<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;

class MonitorController extends Controller
{
    public function index()
    {
        // Eager loading user dan activity agar tidak berat (N+1 Problem)
        $logs = ActivityLog::with(['user', 'activity'])
            ->latest()
            ->paginate(15);

        // Leaderboard berdasarkan Level dan XP
        $topUsers = User::where('role', 'user')
            ->orderByDesc('level')
            ->orderByDesc('current_exp')
            ->take(5)
            ->get();

        return view('monitor.index', compact('logs', 'topUsers'));
    }
}