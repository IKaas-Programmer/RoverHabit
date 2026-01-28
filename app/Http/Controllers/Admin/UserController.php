<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input search
        $search = $request->input('search');

        // Query User dengan fitur Pencarian
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->orderBy('id', 'asc')
            ->paginate(10); // Gunakan paginasi agar tidak berat

        return view('admin.list_users.index', compact('users', 'search'));
    }
}