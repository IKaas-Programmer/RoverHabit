<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Pada update, biasanya kita tetap require, tapi bisa juga 'sometimes'
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'exp_reward' => 'required|integer|min:1',
            'icon' => 'required|in:main,character,exploration,event,side',
        ];
    }
}