<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:users,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'contract_type' => ['required', 'in:percentage,per_session,per_student,monthly'],
            'rate' => ['required', 'numeric', 'min:0'],
        ];
    }
}
