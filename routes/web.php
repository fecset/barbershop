<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPanelController;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/

Route::get('/admin-panel/dashboard', [AdminPanelController::class, 'index'])->name('admin.index');
Route::get('/admin-panel/auth', [AdminPanelController::class, 'auth'])->name('admin.auth');

Route::get('/', function () {
    return view('site.index');
})->name('home');

//---------------------------------------------------------------------
// Маршрут для записи
Route::get('/record', [AppointmentController::class, 'create'])->name('appointments.create'); // Показываем форму записи
Route::post('/record', [AppointmentController::class, 'store'])->name('appointments.store'); // Сохраняем запись

//---------------------------------------------------------------------

Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'registerPost']);

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginPost']);

Route::post('logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

//---------------------------------------------------------------------
Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

Route::get('/masters-by-service/{service}', [AppointmentController::class, 'getMastersByService'])
    ->name('masters.by.service');

