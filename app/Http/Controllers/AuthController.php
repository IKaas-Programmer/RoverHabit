<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // REGISTER SYSTEM SECTION
    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Progress Save New User
    public function register(Request $request)
    {
        // Input Validate 
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        // Save into Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Wajib di-Hash!
            // Field gamifikasi (level, xp) otomatis pakai default value dari migration
        ]);

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // LOGIN SYSTEM SECTION
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Input Validate
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to login | Magic Laravel : Check Mail & Password automatically
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Redirect to intended page or dashboard
            return redirect()->intended('dashboard');
        }

        // If Fail
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // LOGOUT SYSTEM SECTION
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}