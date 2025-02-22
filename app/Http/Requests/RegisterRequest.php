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
            'email' => 'required|email',
            'phone' => 'required|min:10|max:11',
            'password' => 'required|min:8',
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
                'unique' => 'Данное поле должно быть формата электронной почты имя@почта.ру',
            ];
    }
}
