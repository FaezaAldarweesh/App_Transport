<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PathController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StudentController;
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

    Route::apiResource('user',UserController::class); 
    Route::get('all_trashed_user', [UserController::class, 'all_trashed_user']);
    Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);
    
    Route::apiResource('student',StudentController::class); 
    Route::get('all_trashed_student', [StudentController::class, 'all_trashed_student']);
    Route::get('restore_student/{student_id}', [StudentController::class, 'restore']);
    Route::delete('forceDelete_student/{student_id}', [StudentController::class, 'forceDelete']);

    Route::apiResource('path',PathController::class); 
    Route::get('all_trashed_path', [PathController::class, 'all_trashed_path']);
    Route::get('restore_path/{path_id}', [PathController::class, 'restore']);
    Route::delete('forceDelete_path/{path_id}', [PathController::class, 'forceDelete']);

    Route::apiResource('station',StationController::class); 
    Route::get('all_trashed_station', [StationController::class, 'all_trashed_station']);
    Route::get('restore_station/{station_id}', [StationController::class, 'restore']);
    Route::delete('forceDelete_station/{station_id}', [StationController::class, 'forceDelete']);

    Route::apiResource('bus',BusController::class); 
    Route::get('all_trashed_bus', [BusController::class, 'all_trashed_bus']);
    Route::get('restore_bus/{bus_id}', [BusController::class, 'restore']);
    Route::delete('forceDelete_bus/{bus_id}', [BusController::class, 'forceDelete']);
    
});
