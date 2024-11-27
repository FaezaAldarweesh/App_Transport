<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BladeController\BusController;
use App\Http\Controllers\BladeController\DriverController;
use App\Http\Controllers\BladeController\StudentController;
use App\Http\Controllers\BladeController\SupervisorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('student', controller: StudentController::class);
Route::get('all_trashed_student', [StudentController::class, 'all_trashed_student'])->name('all_trashed_student');
Route::get('restore_student/{student_id}', [StudentController::class, 'restore'])->name('restore');
Route::delete('forceDelete_student/{student_id}', [StudentController::class, 'forceDelete'])->name('forceDelete');

Route::resource('supervisor',SupervisorController::class); 
Route::get('all_trashed_supervisor', [SupervisorController::class, 'all_trashed_supervisor'])->name('all_trashed_supervisor');
Route::get('restore_supervisor/{supervisor_id}', [SupervisorController::class, 'restore'])->name('restore');
Route::delete('forceDelete_supervisor/{supervisor_id}', [SupervisorController::class, 'forceDelete'])->name('forceDelete');

Route::Resource('driver',DriverController::class); 
Route::get('all_trashed_driver', [DriverController::class, 'all_trashed_driver'])->name('all_trashed_driver');
Route::get('restore_driver/{driver_id}', [DriverController::class, 'restore'])->name('restore');
Route::delete('forceDelete_driver/{driver_id}', [DriverController::class, 'forceDelete'])->name('forceDelete');

Route::Resource('bus',BusController::class); 
Route::get('all_trashed_bus', [BusController::class, 'all_trashed_bus'])->name('all_trashed_bus');
Route::get('restore_bus/{bus_id}', [BusController::class, 'restore'])->name('restore');
Route::delete('forceDelete_bus/{bus_id}', [BusController::class, 'forceDelete'])->name('forceDelete');