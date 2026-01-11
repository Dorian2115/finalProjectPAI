<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Imię jest wymagane.',
            'email.required' => 'Email jest wymagany.',
            'email.email' => 'Email musi być poprawny.',
            'password.required' => 'Hasło jest wymagane.',
            'password.min' => 'Hasło musi zawierać co najmniej 8 znaków.',
            'password.max' => 'Hasło może zawierać maksymalnie 255 znaków.',
        ];
    }
}
