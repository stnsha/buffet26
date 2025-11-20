<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // User Management
    Route::get('/users', [UserRoleController::class, 'index'])->name('users.index');
    Route::post('/users', [UserRoleController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserRoleController::class, 'update'])->name('users.update');
    Route::delete('/user-roles/{userRole}', [UserRoleController::class, 'destroy'])->name('user-roles.destroy');
    Route::post('/users/{user}/assign-venue', [UserRoleController::class, 'assignVenue'])->name('users.assign-venue');
    Route::post('/users/{user}/remove-venue', [UserRoleController::class, 'removeVenue'])->name('users.remove-venue');
});

