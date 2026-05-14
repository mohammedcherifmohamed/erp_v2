<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'grade_id' => ['sometimes', 'exists:grades,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:100'],
            'capacity' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'is_public' => ['sometimes', 'boolean'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
