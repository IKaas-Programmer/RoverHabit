<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sedang login
        if (Auth::check()) {
            $user = Auth::user();
            // Update waktu terakhir aktif & set status aktif
            // (Menggunakan updateQuietly agar tidak mengubah updated_at terus menerus)
            $user->updateQuietly([
                'last_active_at' => now(),
                'status' => 'aktif'
            ]);
        }
        return $next($request);
    }
}