<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => ['required', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Пожалуйста, введите номер телефона',
            'phone.size' => 'Номер телефона должен содержать 11 цифр',
            'phone.regex' => 'Номер телефона должен содержать только цифры',
            'password.required' => 'Пожалуйста, введите пароль',
        ];
    }
}
