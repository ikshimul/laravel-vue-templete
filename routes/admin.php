<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::post('users/create', [UserController::class, 'create'])->name('user.create');
    Route::get('users/list', [UserController::class, 'List'])->name('users.list');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('profile/information', [ProfileController::class, 'profileInformation'])->name('profile.information');
    Route::post('profile/information/update', [ProfileController::class, 'profileInformationUpdate'])->name('profile.information.update');
    Route::post('password/update', [ProfileController::class, 'passwordUpdate'])->name('password.update');
    Route::post('users/{id}/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::post('users/{user}/info', [UserController::class, 'updateInfo'])->name('user.info.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.delete');
});
