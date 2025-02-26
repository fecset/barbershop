<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Service;
/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/


Route::get('/admin-panel/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin-panel/login', [LoginAdminController::class, 'login'])->name('admin.login');
Route::post('/admin-panel/login', [LoginAdminController::class, 'loginPost']);

Route::post('/admin-panel/logout', function () {
    auth('superadmin')->logout();
    auth('admin')->logout();

    return redirect()->route('admin.login');
})->name('admin.logout');


Route::get('/', function () {
    return view('site.index');
})->name('home');

//---------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    Route::get('/record', [AppointmentController::class, 'create'])->name('appointments.create'); // Показываем форму записи
    Route::post('/record', [AppointmentController::class, 'store'])->name('appointments.store'); // Сохраняем запись
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index'); // Список записей
});


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


//---------------------------------------------------------------------
