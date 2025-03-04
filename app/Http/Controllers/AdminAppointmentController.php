<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Master;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    public function getAllAppointments()
    {
        $appointments = Appointment::all(); // Получаем все записи из базы данных
        return response()->json($appointments); // Возвращаем данные в формате JSON
    }

    public function index()
    {
        $appointments = Appointment::with(['client', 'master', 'service'])->get();
        return response()->json([
            'appointments' => $appointments,
            'clients' => Client::all(),
            'masters' => Master::all(),
            'services' => Service::all(),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Обновляем статус записи
        $appointment->update([
            'статус' => $request->input('статус')
        ]);

        return response()->json(['message' => 'Статус обновлен']);
    }

    private function createOrUpdateClient($clientData)
    {
        // Очищаем номер телефона от "+" в начале
        $phone = ltrim($clientData['телефон'], '+');
        
        // Ищем клиента по телефону
        $client = Client::where('телефон', $phone)->first();

        if ($client) {
            // Обновляем существующего клиента, но не меняем телефон
            $client->update([
                'имя' => $clientData['имя'],
                'фамилия' => $clientData['фамилия']
            ]);
        } else {
            // Создаем нового клиента
            $client = Client::create([
                'имя' => $clientData['имя'],
                'фамилия' => $clientData['фамилия'],
                'телефон' => $phone,
                'email' => null
            ]);
        }

        return $client;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'мастер_id' => 'required|exists:masters,мастер_id',
                'услуга_id' => 'required|exists:services,услуга_id',
                'дата_время' => 'required|date',
                'client_name' => 'required|string',
                'client_phone' => 'required|string'
            ]);

            // Разделяем полное имя на имя и фамилию
            $nameParts = explode(' ', $validated['client_name']);
            $firstName = $nameParts[0];
            $lastName = count($nameParts) > 1 ? $nameParts[1] : $firstName;

            // Создаем или обновляем клиента
            $client = $this->createOrUpdateClient([
                'имя' => $firstName,
                'фамилия' => $lastName,
                'телефон' => $validated['client_phone']
            ]);

            // Генерируем ID записи
            $lastAppointment = Appointment::orderBy('запись_id', 'desc')->first();
            $nextId = $lastAppointment ? intval($lastAppointment->запись_id) + 1 : 1;

            // Создаем запись
            $appointment = Appointment::create([
                'запись_id' => $nextId,
                'клиент_id' => $client->клиент_id,
                'мастер_id' => $validated['мастер_id'],
                'услуга_id' => $validated['услуга_id'],
                'дата_время' => $validated['дата_время'],
                'статус' => 'Подтверждена'
            ]);

            return response()->json([
                'message' => 'Запись успешно создана',
                'appointment' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ошибка при создании записи',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            
            $validated = $request->validate([
                'мастер_id' => 'required|exists:masters,мастер_id',
                'услуга_id' => 'required|exists:services,услуга_id',
                'дата_время' => 'required|date',
                'client_name' => 'required|string',
                'client_phone' => 'required|string'
            ]);

            // Разделяем полное имя на имя и фамилию
            $nameParts = explode(' ', $validated['client_name']);
            $firstName = $nameParts[0];
            $lastName = count($nameParts) > 1 ? $nameParts[1] : $firstName;

            // Создаем или обновляем клиента
            $client = $this->createOrUpdateClient([
                'имя' => $firstName,
                'фамилия' => $lastName,
                'телефон' => $validated['client_phone']
            ]);

            // Обновляем запись
            $appointment->update([
                'клиент_id' => $client->клиент_id,
                'мастер_id' => $validated['мастер_id'],
                'услуга_id' => $validated['услуга_id'],
                'дата_время' => $validated['дата_время'],
                'статус' => 'Подтверждена'
            ]);

            return response()->json([
                'message' => 'Запись успешно обновлена',
                'appointment' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ошибка при обновлении записи',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted']);
    }
}
