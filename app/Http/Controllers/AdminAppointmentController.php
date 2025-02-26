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


    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted']);
    }
}
