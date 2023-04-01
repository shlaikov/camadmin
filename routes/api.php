<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\CamundaController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    Route::prefix('instances')->group(function () {
        Route::get('/', [InstanceController::class, 'index']);
        Route::any('/camunda/{id}/{request}', CamundaController::class)
            ->where('id', '[0-9]+')
            ->where('request', '.*');
    });
});
