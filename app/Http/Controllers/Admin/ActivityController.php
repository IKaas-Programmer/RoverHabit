<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Http\Requests\Admin\StoreActivityRequest;
use App\Http\Requests\Admin\UpdateActivityRequest;

class ActivityController extends Controller
{
    // show list of activities
    public function index(Request $request)
    {
        // 1. Ambil parameter 'filter' dari URL (defaultnya null)
        $filter = $request->query('filter');

        // 2. Mulai Query dasar
        $query = Activity::query();

        // 3. Jika ada filter dan bukan 'all', tambahkan kondisi WHERE
        if ($filter && $filter !== 'all') {
            $query->where('icon', $filter);
        }

        // 4. Ambil datanya (Pagination 10 per halaman)
        $activities = $query->latest()->paginate(10);

        // Append query string agar saat pindah page 2, filternya tidak hilang
        $activities->appends(['filter' => $filter]);
        return view('admin.activities.index', compact('activities'));
    }

    // show create activity form
    public function create()
    {
        return view('admin.activities.create');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    // Save new activity to database
    public function store(StoreActivityRequest $request)
    {
        // Validasi sudah otomatis dijalankan oleh StoreActivityRequest.
        // Jika gagal, Laravel otomatis menendang balik ke form.
        // Jika lolos, kode di bawah ini baru jalan.

        Activity::create($request->validated());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Quest baru berhasil dibuat!');
    }

    // Update existing activity
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Quest berhasil diperbarui!');
    }

    // Soft Delete activity
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Quest berhasil dipindahkan ke Trash Bin!');
    }

    public function trash()
    {
        $activities = Activity::onlyTrashed()->latest()->paginate(10);
        return view('admin.activities.trash', compact('activities'));
    }

    public function restore($id)
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->restore();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Quest berhasil dipulihkan kembali!');
    }

    //  Hapus Permanen (Force Delete)
    public function forceDelete($id)
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->forceDelete();

        return redirect()->route('admin.activities.trash')
            ->with('success', 'Quest telah dimusnahkan selamanya.');
    }
}