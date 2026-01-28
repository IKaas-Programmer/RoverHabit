<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Requests\Admin\StoreActivityRequest;
use App\Http\Requests\Admin\UpdateActivityRequest;

class ActivityController extends Controller
{
    // show list of activities
    public function index()
    {
        $activities = Activity::all();
        return view('admin.activities.index', compact('activities'));
    }

    // show create activity form
    public function create()
    {
        return view('admin.activities.create');
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

    public function trash()
    {
        $activities = Activity::onlyTrashed()->latest()->get();
        return view('admin.activities.trash', compact('activities'));
    }

    public function restore($id)
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->restore();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Quest berhasil dibangkitkan kembali!');
    }

    // 3. Hapus Permanen (Force Delete)
    public function forceDelete($id)
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->forceDelete();

        return redirect()->route('admin.activities.trash')
            ->with('success', 'Quest telah dimusnahkan selamanya.');
    }
}