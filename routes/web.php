<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminNumberController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Convidados
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthViewController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthViewController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| Usuário autenticado
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/numeros', [NumberController::class, 'index'])->name('numbers');
    Route::post('/numeros/reservar', [NumberController::class, 'reserve'])->name('numbers.reserve');
});

/*
|--------------------------------------------------------------------------
| Área administrativa
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/reservas', [AdminReservationController::class, 'index'])->name('admin.reservations');
    Route::post('/reservas/{reservation}/confirmar', [AdminReservationController::class, 'confirm'])
        ->name('admin.reservations.confirm');
    Route::post('/reservas/{reservation}/cancelar', [AdminReservationController::class, 'cancel'])
        ->name('admin.reservations.cancel');

    Route::get('/configuracoes', [AdminConfigController::class, 'index'])->name('admin.settings');
    Route::post('/configuracoes', [AdminConfigController::class, 'update'])->name('admin.settings.update');

    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/usuarios/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.role');
    Route::post('/usuarios/{user}/senha', [AdminUserController::class, 'updatePassword'])->name('admin.users.password');

    Route::get('/numeros', [AdminNumberController::class, 'index'])->name('admin.numbers');
    Route::post('/numeros/{id}/status', [AdminNumberController::class, 'updateStatus'])->name('admin.numbers.status');

    Route::get('/logs', [AdminLogController::class, 'index'])->name('admin.logs');
});