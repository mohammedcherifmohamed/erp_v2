<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function authenticate(): void
    {
        $user = \App\Models\User::where('email', $this->email)->first();

        if ($user && $user->isTeacher()) {
            $user->load('teacherProfile');

            if ($user->teacherProfile && $user->teacherProfile->isPending()) {
                throw ValidationException::withMessages([
                    'email' => __('Votre candidature est en cours d\'examen. Vous recevrez une notification dès qu\'elle sera approuvée par l\'administration.'),
                ]);
            }

            if ($user->teacherProfile && $user->teacherProfile->isRejected()) {
                throw ValidationException::withMessages([
                    'email' => __('Votre candidature a été refusée. Veuillez contacter l\'administration pour plus d\'informations.'),
                ]);
            }
        }

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}