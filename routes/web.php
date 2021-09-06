<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
//! Laravel Yajra Routes 
Route::get('/yajra', [EmployeeController::class, 'index'])->name('yajra');
Route::get('/employee', [EmployeeController::class, 'getEmployee'])->name('employee.list');
Route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
Route::get('/employee/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
Route::post('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
Route::post('/employee', [EmployeeController::class, 'multipleDelete'])->name('employee.multipleDelete');

//! Datatable 
Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/home-data', [HomeController::class, 'getHomeData'])->name('getHomeData');
Route::post('/add/home-data', [HomeController::class, 'addHomeData'])->name('addHomeData');
Route::post('/delete/home-data/{id}', [HomeController::class, 'deleteHomeData'])->name('deleteHomeData');
Route::post('/delete/home-data', [HomeController::class, 'deleteMultiRecords'])->name('deleteMultiRecords');
Route::get('/home-data/{id}', [HomeController::class, 'getSingleRecord'])->name('getSingleRecord');
Route::post('/home-data/{id}', [HomeController::class, 'updateSingleRecord'])->name('updateSingleRecord');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//! Student Routes
Route::get('/student', [StudentController::class, 'index'])->name('student');
Route::get('/students', [StudentController::class, 'show'])->name('student.list');
Route::post('/student', [StudentController::class, 'store'])->name('student.add');
Route::post('/country/{id}', [StateController::class, 'getCountry'])->name('country.get');
Route::get('/state/{state}', [StateController::class, 'getState'])->name('state.get');
Route::get('/country/{country}', [CountryController::class, 'get'])->name('country.get.first');
Route::get('/student/{id}', [StudentController::class, 'destroy'])->name('student.delete');
