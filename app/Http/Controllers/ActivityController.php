<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreQuestRequest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function execute(Activity $activity)
    {
        $user = Auth::user();

        // Cek Anti-Spam (Satu quest hanya bisa selesai 1x per hari)
        $exists = ActivityLog::where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Quest sudah selesai hari ini.');
        }

        // Pastikan relasi atribut dimuat
        $activity->load('attributes');

        // Jalankan Database Transaction
        return DB::transaction(function () use ($user, $activity) {

            // Tambah XP Utama & Catat Log 
            $user->addXp($activity->exp_reward);

            ActivityLog::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'xp_gained' => $activity->exp_reward,
                'note' => 'Quest Completed: ' . $activity->name
            ]);

            // DISTRIBUSI XP KE ATRIBUT (Logic Hybrid )
            foreach ($activity->attributes as $attribute) {
                // Rumus: XP Atribut = XP Quest * Bobot (Weight)
                $xpForThisAttribute = (int) ($activity->exp_reward * $attribute->pivot->weight);

                // Update XP di tabel pivot user_attributes
                // updateExistingPivot akan menambah XP pada row yang sudah ada
                $user->characterStats()->updateExistingPivot($attribute->id, [
                    'current_xp' => DB::raw("current_xp + $xpForThisAttribute")
                ]);
            }

            return redirect()->back()->with('success', "Quest Clear! +{$activity->exp_reward} XP distributed to attributes.");
        });
    }

    // Inject StoreQuestRequest ke dalam method
    public function store(StoreQuestRequest $request)
    {
        // Jika sampai di sini, berarti data SUDAH tervalidasi otomatis
        return DB::transaction(function () use ($request) {
            $activity = Activity::create($request->validated());

            // Mapping Atribut & Bobot ke tabel pivot
            $syncData = [];
            foreach ($request->attributes as $index => $attrId) {
                $syncData[$attrId] = [
                    'weight' => $request->weights[$index] ?? 0
                ];
            }

            $activity->attributes()->sync($syncData);

            return redirect()->route('dashboard')->with('success', 'Quest Forged!');
        });
    }
}