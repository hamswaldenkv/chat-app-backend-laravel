<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\SigninController;
use App\Http\Controllers\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::resource('events', EventController::class);

    Route::prefix('auth')->group(function () {
        Route::resource('login', SigninController::class);
        Route::resource('signup', SignupController::class);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('events.participants', ParticipantController::class)->shallow();
        Route::resource('devices', DeviceController::class);
        Route::resource('messages', MessageController::class);
        Route::resource('chats', ChatController::class);
        Route::resource('assets', AssetController::class);
    });
});
