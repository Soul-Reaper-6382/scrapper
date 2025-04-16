<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaveDataController;

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

Route::post('/save-data', [SaveDataController::class, 'store']);
Route::post('/retrieve-data', [SaveDataController::class, 'retrieve']);
Route::post('/retrieve-alldata', [SaveDataController::class, 'retrieve_alldata']);
Route::post('/send-data', [SaveDataController::class, 'send_data']);