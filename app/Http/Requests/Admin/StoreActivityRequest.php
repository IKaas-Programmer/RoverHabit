<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh melakukan request ini.
     * Ubah jadi true karena kita sudah memfilter admin lewat Middleware/Route.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi (Rules)
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'exp_reward' => 'required|integer|min:1',
            'icon' => 'required|in:main,character,exploration,event,side',
            // Kita validasi Icon agar hanya menerima kategori WuWa (Wuthering Waves) style
        ];
    }

    /**
     * Pesan error custom (Opsional, agar lebih ramah manusia)
     */
    public function messages(): array
    {
        return [
            'icon.in' => 'Kategori quest tidak valid! Pilih salah satu warna yang tersedia.',
            'name.required' => 'Nama Quest wajib diisi, Kapten!',
        ];
    }
}