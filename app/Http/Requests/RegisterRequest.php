<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'phone' => 'required|string|size:11|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:8|regex:/[A-Z]/|regex:/\d.*\d.*\d.*\d/',
        ];
    }

    public function messages()
    {
        return parent::messages() +
            [
                'required' => 'Данное поле должно быть заполнено',
                'email' => 'Данное поле должно быть формата электронной почты имя@почта.ру',
                'min' => 'Минимальное количество символов :min',
                'max' => 'Максимальное количество символов :max',
                'unique' => 'Этот телефон уже зарегистрирован',
                'phone.size' => 'Номер телефона должен содержать 11 цифр',
                'password.regex' => 'Пароль должен содержать минимум одну заглавную букву и не менее 4 цифр.',
            ];
    }
}
