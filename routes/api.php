<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiCustomerController;
use App\Http\Controllers\ApiEventsController;
use App\Http\Controllers\Api\ApiProspekController;

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

// routes/api.php
Route::patch('/customers/{id}/update-fields', [ApiCustomerController::class, 'updateFields']);

// --- [ GUNAKAN RUTE BARU INI ] ---
Route::get('/events/check-customer', [ApiEventsController::class, 'checkCustomerEventStatus']);
Route::post('/events/action', [ApiEventsController::class, 'updateInvitationAction']);
Route::get('/events/officer-progress', [ApiEventsController::class, 'getOfficerProgress']);

Route::post('/v1/prospek/receive', [\App\Http\Controllers\Api\ApiProspekController::class, 'receive']);

Route::get('/v1/prospek/my-data', [\App\Http\Controllers\Api\ApiProspekController::class, 'getMyData']);

Route::post('/v1/prospek/update-insight/{id}', [\App\Http\Controllers\Api\ApiProspekController::class, 'updateInsight']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
