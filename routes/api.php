<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\SportEventController;
use App\Http\Controllers\AuthController;
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

// Organizers Endpoints with /v1 prefix
Route::prefix('v1')->group(function () {
    Route::resource('organizers', OrganizerController::class);
});

// Sport Events Endpoints with /v1 prefix
Route::prefix('v1')->group(function () {
    Route::resource('sport-events', SportEventController::class);
});

// Users Endpoints with /v1 prefix
Route::prefix('v1')->group(function () {
    Route::resource('users', UserController::class);
});


// Ignore Above code, I miss read the instruction i thought implement it from the scratch


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

