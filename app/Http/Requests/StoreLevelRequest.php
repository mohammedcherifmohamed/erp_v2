<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:levels,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

class UpdateLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('levels', 'code')->ignore($this->route('level'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'level_id' => ['required', 'exists:levels,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:grades,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'level_id' => ['sometimes', 'exists:levels,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('grades', 'code')->ignore($this->route('grade'))],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

class StoreClasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'grade_id' => ['required', 'exists:grades,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'is_public' => ['sometimes', 'boolean'],
            'price' => ['required_if:is_public,true', 'nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
        ];
    }
}

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

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:courses,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'teacher_id' => ['required', 'exists:users,id'],
            'sessions_count' => ['nullable', 'integer', 'min:0'],
            'credits' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }
}

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'exists:classes,id', 'exists:classes,id,is_active,1'],
            'student_id' => ['required', 'exists:users,id'],
        ];
    }
}

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'classroom' => ['nullable', 'string', 'max:100'],
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }
}

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

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}

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

class StoreAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => ['required'],
        ];
    }
}

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTeacher() || auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'class_id' => ['nullable', 'required_unless:is_global,true', 'exists:classes,id'],
            'is_global' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}

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
            'contract_type' => ['required', 'in:percentage,per_session,per_student,fixed_salary'],
            'rate' => ['required', 'numeric', 'min:0'],
        ];
    }
}

class StoreWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isTeacher();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:255'],
        ];
    }
}