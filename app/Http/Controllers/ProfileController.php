<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function updateAvatar(Request $request)
    {
        // Pastikan yang diupload adalah GAMBAR (bukan pdf/exe) & Max 2MB
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada (Biar server tidak penuh sampah)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder 'storage/app/public/avatars'
            // Laravel otomatis akan membuatkan nama file unik yang acak
            $path = $request->file('avatar')->store('avatars', 'public');

            // Simpan ALAMAT file tersebut ke Database
            $user->avatar = $path;
            $user->save();

            // Jika permintaan via AJAX (Fetch), kirim JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Avatar updated.',
                    'path' => Storage::url($path)
                ]);
            }
        }

        return redirect()->back()->with('success', 'Avatar updated successfully.');
    }
}