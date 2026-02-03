<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'exp_reward' => 'required|integer|min:10',
            'attributes' => 'required|array',
            'weights' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Menggunakan round untuk menghindari masalah presisi floating point
                    if (round(array_sum($value), 1) != 1.0) {
                        $fail('Total weight allocation must be exactly 100% (1.0).');
                    }
                },
            ],
        ];
    }

    /**
     * Custom error messages agar UI lebih informatif.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Quest title is mandatory for your journey.',
            'attributes.required' => 'You must assign at least one attribute to this quest.',
            'exp_reward.min' => 'A worthy quest must grant at least 10 XP.',
        ];
    }
}