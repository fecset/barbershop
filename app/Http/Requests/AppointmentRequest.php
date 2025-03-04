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
            'мастер_id' => 'required|exists:masters,мастер_id',
            'услуга_id' => 'required|exists:services,услуга_id',
            'дата_время' => 'required|date'
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'мастер_id.required' => 'Пожалуйста, выберите мастера',
            'мастер_id.exists' => 'Выбранный мастер не существует',
            'услуга_id.required' => 'Пожалуйста, выберите услугу',
            'услуга_id.exists' => 'Выбранная услуга не существует',
            'дата_время.required' => 'Пожалуйста, выберите дату и время',
            'дата_время.date' => 'Неверный формат даты и времени'
        ];
    }
}
