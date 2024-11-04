<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ClassRoomController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

Route::group(['middleware' => ['auth:api']], function () {
    // protected routes go here
    Route::post('logout',[AuthController::class ,'logout']); 
    Route::post('refresh', [AuthController::class ,'refresh']);
    

    Route::apiResource('grade',GradeController::class); 
    Route::get('all_trashed_grade', [GradeController::class, 'all_trashed_grade']);
    Route::get('restore_grade/{grade_id}', [GradeController::class, 'restore']);
    Route::delete('forceDelete_grade/{grade_id}', [GradeController::class, 'forceDelete']);


    Route::apiResource('class',ClassRoomController::class); 
    Route::get('all_trashed_class', [ClassRoomController::class, 'all_trashed_class']);
    Route::get('restore_class/{class_id}', [ClassRoomController::class, 'restore']);
    Route::delete('forceDelete_class/{class_id}', [ClassRoomController::class, 'forceDelete']);


    // Route::apiResource('user',UserController::class); 
    // Route::get('all_trashed_user', [UserController::class, 'all_trashed_user']);
    // Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    // Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);

});
