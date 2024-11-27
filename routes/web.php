<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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