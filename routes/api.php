<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ErrorsController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});

Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])
->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::group(
    [
        'middleware' => 'auth:sanctum'
    ], function () {
        Route::get('/programs', [ProgramController::class, 'index']);
        Route::get('/messages', [MessageController::class, 'index']);
        Route::get('/attend', [AttendanceController::class, 'index']);
        Route::get('/errors', [ErrorsController::class,'index']);
        Route::get('/home', [HomeController::class,'index']);
});
