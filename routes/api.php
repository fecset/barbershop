<?php

use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MasterController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/master-schedule/{masterId}', [AppointmentController::class, 'getMasterSchedule']);


Route::get('/services', [ServiceController::class, 'index']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
Route::post('/services', [ServiceController::class, 'store']);
Route::get('/services/last-id', [ServiceController::class, 'getLastServiceId']);


Route::put('/services/{id}', function (Request $request, $id) {
    $service = Service::find($id);
    if (!$service) {
        return response()->json(['error' => 'Service not found'], 404);
    }

    $service->update([
        'название' => $request->input('название'),
        'цена' => $request->input('цена'),
        'специализация' => $request->input('специализация')
    ]);
    return response()->json($service, 200);
});

Route::get('/masters', [MasterController::class, 'index']);
Route::post('/masters', [MasterController::class, 'store']);
Route::delete('/masters/{id}', [MasterController::class, 'destroy']);
Route::put('/masters/{id}', [MasterController::class, 'update']);

Route::get('/appointments', [AdminAppointmentController::class, 'getAllAppointments']);
Route::get('/appointments', [AdminAppointmentController::class, 'index']);
Route::put('/appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus']);
Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
Route::post('/admin/appointments', [AdminAppointmentController::class, 'store']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy']);

Route::get('/admins', [AdminController::class, 'index']);
Route::post('/admins', [AdminController::class, 'store']);
Route::put('/admins/{id}', [AdminController::class, 'update']);
Route::delete('/admins/{id}', [AdminController::class, 'destroy']);

Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
