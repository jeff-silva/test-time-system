<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('app/openapi', [Controllers\AppController::class, 'openapi'])->name('app.openapi');

Route::post('auth/login', [Controllers\AuthController::class, 'login'])->name('auth.login');
Route::post('auth/logout', [Controllers\AuthController::class, 'logout'])->name('auth.logout');
Route::post('auth/refresh', [Controllers\AuthController::class, 'refresh'])->name('auth.refresh');
Route::post('auth/me', [Controllers\AuthController::class, 'me'])->name('auth.me');

Route::apiResource('todo_project', Controllers\TodoProjectController::class);
Route::apiResource('todo_project_task', Controllers\TodoProjectTaskController::class);
