<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReceptionController;
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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::group([
        'middleware' => ['auth_user:api'],
    ], function () {
        Route::post('addReception', [ReceptionController::class, 'store'])->middleware('admin');
        Route::get('receptions', [ReceptionController::class, 'index']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

});
Route::group([
    'prefix' => 'doctors',
    'middleware' => ['auth_user:api']
], function () {
    Route::post('addDoctor', [DoctorController::class, 'store'])->middleware('admin');
    Route::get('doctors', [DoctorController::class, 'index']);
    Route::get('departmentDoctors/{id}', [DoctorController::class, 'departmentDoctors']);
    Route::get('searchByName', [DoctorController::class, 'search']);
    Route::delete('doctor/{id}', [DoctorController::class, 'destroy'])->middleware('admin');

});
Route::group([
    'prefix' => 'departments',
    'middleware' => ['auth_user:api']
], function () {
    Route::post('addDepartment', [DepartmentController::class, 'store'])->middleware('admin');
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('departmentsWithDoctors', [DepartmentController::class, 'departmentsWithDoctors    ']);
    Route::get('searchByName', [DepartmentController::class, 'search']);
    Route::delete('department/{id}', [DepartmentController::class, 'destroy'])->middleware('admin');
});
Route::group([
    'prefix' => 'equipments',
    'middleware' => ['auth_user:api']
], function () {
    Route::post('addEquipment', [EquipmentController::class, 'store'])->middleware('admin');
});
Route::group([
    'prefix' => 'patients',
    'middleware' => ['auth_user:api']
], function () {
    Route::post('addPatientForm', [PatientController::class, 'store'])->middleware('reception');
    Route::post('accept/{id}', [PatientController::class, 'accept'])->middleware('doctor');
    Route::post('updateForm/{id}', [PatientController::class, 'update'])->middleware('doctor');
    Route::post('reject/{id}', [PatientController::class, 'reject'])->middleware('doctor');
    Route::get('patients', [PatientController::class, 'index'])->middleware('admin');
    Route::get('patient/{id}', [PatientController::class, 'show']);
    Route::get('doctorPatient/{id}', [PatientController::class, 'doctorPatient']);
    Route::get('searchByName', [PatientController::class, 'search']);
    Route::get('okPatient', [PatientController::class, 'okPatient']);
    Route::delete('patient/{id}', [PatientController::class, 'destroy'])->middleware('admin');
});
