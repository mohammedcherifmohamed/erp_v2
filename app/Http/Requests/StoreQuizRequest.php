<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTeacher();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'course_id' => ['required', 'exists:courses,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'passing_points' => ['nullable', 'integer', 'min:0'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:180'],
            'available_from' => ['nullable', 'date'],
            'available_until' => ['nullable', 'date', 'after_or_equal:available_from'],
            'is_published' => ['sometimes', 'boolean'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string', 'max:2000'],
            'questions.*.type' => ['required', 'in:multiple_choice,true_false,text'],
            'questions.*.options' => ['required_if:questions.*.type,multiple_choice', 'nullable', 'array', 'min:2', 'max:10'],
            'questions.*.correct_answer' => ['nullable', 'string', 'max:500'],
            'questions.*.points' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
