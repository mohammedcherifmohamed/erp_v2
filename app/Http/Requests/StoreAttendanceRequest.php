<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTeacher() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*.student_id' => ['required', 'exists:users,id'],
            'attendances.*.status' => ['required', 'in:present,absent,late,excused'],
            'attendances.*.notes' => ['nullable', 'string', 'max:500'],
            'course_id' => ['required', 'exists:courses,id'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
