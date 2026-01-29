<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ListUserController extends Controller
{
    /**
     * Menampilkan daftar user (Player).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Filter Admin (PENTING: Jangan tampilkan admin)
        // 2. Gunakan 'when' ala Data B (Lebih rapi)
        $users = User::where('role', '!=', 'admin')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest() // Tampilkan user terbaru dulu (UX lebih baik)
            ->paginate(10);

        // compact('search') agar tulisan di kolom cari tidak hilang saat refresh
        return view('admin.list_users.index', compact('users', 'search'));
    }

    /**
     * Banned User (Soft Delete).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Safety extra: Pastikan yang dihapus bukan admin (cegah hacking via inspect element)
        if ($user->role === 'admin') {
            return back()->with('error', 'Anda tidak bisa mem-banned sesama Admin!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil di-banned (Soft Delete)!');
    }

    /**
     * Menampilkan User yang sudah di-banned (Trash).
     */
    public function trash()
    {
        $users = User::onlyTrashed()->where('role', '!=', 'admin')->paginate(10);

        // Pastikan nama folder view sesuai dengan folder yang Anda buat di resources
        return view('admin.list_users.trash', compact('users'));
    }

    /**
     * Restore User (Un-banned).
     */
    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.list_users.trash')->with('success', 'User berhasil dipulihkan!');
    }
}