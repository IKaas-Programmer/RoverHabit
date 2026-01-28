<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;

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
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'exp_reward' => 'required|integer|min:1',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Create new activity
        Activity::create($request->all());

        // Redirect to activities list with success message
        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity  created successfully!');
    }
}