<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Master;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('клиент_id', Auth::id())->get();
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'услуга_id' => 'required|exists:services,услуга_id',
            'мастер_id' => 'required|exists:masters,мастер_id',
            'дата_время' => 'required|date|after:today',
        ]);
        $validated['клиент_id'] = Auth::id();
        $validated['статус'] = 'Ожидает подтверждения';
        Appointment::create($validated);

        return redirect()->route('profile')->with('success', 'Запись успешно создана.');
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


}
