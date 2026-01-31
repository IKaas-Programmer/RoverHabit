<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Memperbarui nama user secara langsung dari Status Window.
     */
    public function updateName(Request $request)
    {
        // 1. Validasi: Pastikan nama tidak kosong dan sesuai kriteria
        $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);

        // 2. Proses Update
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }
        $user->name = $request->name;
        $user->save();

        // 3. Kembali dengan feedback
        return redirect()->back()->with('success', 'Name updated successfully.');
    }
}