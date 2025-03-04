<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Master;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $client = \App\Models\Client::where('email', Auth::user()->email)->first();
        if (!$client) {
            return view('appointments.index', ['appointments' => []]);
        }
        $appointments = Appointment::where('клиент_id', $client->клиент_id)->get();
        return view('appointments.index', compact('appointments'));
    }

    public function getMastersByService($serviceId)
    {
        try {
            \Log::info("Запрос мастеров для услуги с ID: $serviceId");

            $service = \App\Models\Service::find($serviceId);

            if (!$service) {
                \Log::error("Услуга с ID $serviceId не найдена.");
                return response()->json([], 404);
            }

            $masters = \App\Models\Master::where('специализация', $service->специализация)->get();

            if ($masters->isEmpty()) {
                \Log::error("Мастера для услуги с ID $serviceId не найдены.");
                return response()->json([], 404);
            }

            return response()->json($masters);

        } catch (\Exception $e) {
            \Log::error('Ошибка в getMastersByService: ' . $e->getMessage());
            return response()->json(['error' => 'Внутренняя ошибка сервера'], 500);
        }
    }

    public function create()
    {
        $masters = Master::all();
        $services = Service::all();

        return view('site.record', compact('masters', 'services'));
    }

    public function store(AppointmentRequest $request)
    {
        try {
            // Генерируем ID записи
            $lastAppointment = Appointment::orderBy('запись_id', 'desc')->first();
            $nextId = $lastAppointment ? intval($lastAppointment->запись_id) + 1 : 1;
            
            // Получаем ID клиента из таблицы clients по email пользователя
            $client = \App\Models\Client::where('email', Auth::user()->email)->first();
            if (!$client) {
                throw new \Exception('Клиент не найден');
            }
            
            $validated = $request->validated();
            $validated['клиент_id'] = $client->клиент_id;
            $validated['запись_id'] = $nextId;
            $validated['статус'] = 'Ожидает подтверждения';
            
            $appointment = Appointment::create($validated);

            return redirect()->route('profile')->with('success', 'Запись успешно создана!');
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании записи: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ошибка при создании записи: ' . $e->getMessage());
        }
    }

    public function destroy($запись_id)
    {
        $appointment = Appointment::findOrFail($запись_id);
        $appointment->delete();

        return redirect()->route('profile')->with('success', 'Запись успешно удалена.');
    }

    public function getMasterSchedule($masterId)
    {
        try {
            $master = Master::find($masterId);

            if (!$master) {
                return response()->json(['error' => 'Мастер не найден'], 404);
            }

            $workSchedule = $master->график_работы;

            if (!$workSchedule) {
                return response()->json(['error' => 'Рабочий график не найден'], 404);
            }

            $schedule = $this->parseSchedule($workSchedule);

            return response()->json($schedule);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера'], 500);
        }
    }

    private function parseSchedule($schedule)
    {
        $daysOfWeek = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        $parsedSchedule = [];

        foreach (explode(', ', $schedule) as $entry) {
            list($daysRange, $times) = explode(' ', $entry);
            list($startDay, $endDay) = explode('-', $daysRange);
            list($startTime, $endTime) = explode('-', $times);

            $startIndex = array_search($startDay, $daysOfWeek);
            $endIndex = array_search($endDay, $daysOfWeek);

            if ($endIndex < $startIndex) {
                $parsedSchedule[] = [
                    'days' => array_merge(range($startIndex, 6), range(0, $endIndex)),
                    'startTime' => $startTime,
                    'endTime' => $endTime
                ];
            } else {
                $parsedSchedule[] = [
                    'days' => range($startIndex, $endIndex),
                    'startTime' => $startTime,
                    'endTime' => $endTime
                ];
            }
        }

        return $parsedSchedule;
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Запись не найдена'], 404);
        }

        $request->validate([
            'мастер_id' => 'required|integer|exists:masters,мастер_id',
            'услуга_id' => 'required|integer|exists:services,услуга_id',
            'дата_время' => 'required|date_format:Y-m-d H:i:s',
            'статус' => 'required|string'
        ]);

        // Обновляем только необходимые поля
        $appointment->мастер_id = $request->мастер_id;
        $appointment->услуга_id = $request->услуга_id;
        $appointment->дата_время = $request->дата_время;
        $appointment->статус = $request->статус;
        
        $appointment->save();

        return response()->json(['message' => 'Запись обновлена', 'appointment' => $appointment]);
    }


}
