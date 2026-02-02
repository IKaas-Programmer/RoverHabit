<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function execute(Activity $activity)
    {
        $user = auth()->user();

        // Cek Anti-Spam
        $exists = ActivityLog::where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Quest sudah selesai hari ini.');
        }

        // Tambah XP & Catat Log
        $user->addXp($activity->exp_reward);
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'xp_gained' => $activity->exp_reward,
            'note' => 'Quest Completed: ' . $activity->name
        ]);

        return redirect()->back()->with('success', "Quest Clear! +{$activity->exp_reward} XP.");
    }
}