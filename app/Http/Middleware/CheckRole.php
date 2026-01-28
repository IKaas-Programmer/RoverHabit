<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated Login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Ambil User saat ini
        $user = Auth::user();

        // 3. Logika Hirarki Akses
        // Admin boleh masuk ke mana saja? Atau strict?
        // Di sini kita buat strict: Role user harus SAMA dengan role yang diminta route.

        // Pengecualian: Jika role yang diminta 'admin', tapi user bukan admin -> TENDANG.
        $role = $request->route('role');
        if ($role == 'admin' && $user->role != 'admin') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN ADMIN!');
        }

        // Pengecualian: Jika role yang diminta 'member' (pengawas)
        if ($role == 'member') {
            if ($user->role != 'member' && $user->role != 'admin') {
                abort(403, 'Halaman Khusus Pengawas & Admin.');
            }
        }

        return $next($request);
    }
}