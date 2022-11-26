<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\HomeInformationController;
use App\Http\Controllers\ICalController;
use App\Http\Controllers\UsosController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
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
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify']);

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/edit', [AuthController::class, 'editProfile']);

    Route::get('/offers', [OfferController::class, 'index']);
    Route::post('/offers/add', [OfferController::class, 'store']);
    Route::delete('/offers/{id}/remove', [OfferController::class, 'destroy']);

    Route::get('/posts', [HomeInformationController::class, 'index']);

    Route::get("/ical-events", [ICalController::class, 'getEventsICalObject']);
    Route::post('/ical-events/add', [ICalController::class, 'store']);
    Route::post('/ical-events/get', [ICalController::class, 'index']);
    Route::post('/ical-events/edit/{id}', [ICalController::class, 'update']);
    Route::delete('/ical-events/remove/{id}', [ICalController::class, 'destroy']);
    
    Route::post('/auth/check-token', [AuthController::class, 'checkToken']);


});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('/calendar-save', [ICalController::class, 'saveCalendarEvents']);

Route::get('/usos-data', [UsosController::class, 'index']);
Route::post('/usos-submit', [UsosController::class, 'authorization']);
Route::post('/usos-access', [UsosController::class, 'accessToken']);
