<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Разрешаем выполнение запроса
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
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\+7\s?\(\d{3}\)\s?\d{3}-\d{2}-\d{2}$/'],
            'услуга_id' => 'required|exists:services,услуга_id',
            'мастер_id' => 'required|exists:masters,мастер_id',
            'дата_время' => 'required|date_format:Y-m-d H:i',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'required' => 'Это поле обязательно для заполнения.',
            'email' => 'Укажите корректный адрес электронной почты.',
            'min' => 'Минимальное количество символов :min.',
            'max' => 'Максимальное количество символов :max.',
            'exists' => 'Выбранная :attribute не существует.',
            'date_format' => 'Неверный формат даты и времени. Используйте формат "ГГГГ-ММ-ДД ЧЧ:ММ".',
            'phone.regex' => 'Номер телефона должен быть в формате +7 (XXX) XXX-XX-XX',
        ];
    }
}
