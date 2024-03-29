<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ICalController;
use App\Http\Controllers\UsosController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\StudyGroupController;
use App\Http\Controllers\StudyFieldsController;
use App\Http\Controllers\MessageController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Translation\MessageCatalogue;

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
    

    Route::post('/offers', [OfferController::class, 'index']);
    Route::post('/offers/add', [OfferController::class, 'store']);
    Route::post('/offer/fav/{id}', [OfferController::class, 'setFavourite']);
    Route::delete('/offers/{id}/remove', [OfferController::class, 'destroy']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/post/add', [PostController::class, 'store']);

    Route::get('/announcements', [AnnouncementsController::class, 'index']);
    Route::post('/announcement/add', [AnnouncementsController::class, 'store']);
    Route::delete('/announcement/{id}/remove', [AnnouncementsController::class, 'destroy']);

    Route::get("/ical-events", [ICalController::class, 'getEventsICalObject']);
    Route::post('/ical-events/add', [ICalController::class, 'store']);
    Route::post('/ical-events/get', [ICalController::class, 'index']);
    Route::post('/ical-events/edit/{id}', [ICalController::class, 'update']);
    Route::delete('/ical-events/remove/{id}', [ICalController::class, 'destroy']);
    
    Route::post('/auth/check-token', [AuthController::class, 'checkToken']);

    Route::get('/study-groups', [StudyGroupController::class, 'index']);

    Route::get('/profile/{id}', [AuthController::class, 'friendProfile']);

    Route::get('/study-fields', [StudyFieldsController::class, 'index']);

    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/message/add', [MessageController::class, 'store']);


});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('/calendar-save', [ICalController::class, 'saveCalendarEvents']);

Route::get('/usos-data', [UsosController::class, 'index']);
Route::post('/usos-submit', [UsosController::class, 'authorization']);
Route::post('/usos-access', [UsosController::class, 'accessToken']);
