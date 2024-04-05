<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;


/**Acceso libre sin autenticacion registro y login de usuarios */
Route::post('auth/register',[AuthController::class, 'create']);
Route::post('auth/login',[AuthController::class, 'login']);


/*Grupos de Rutas Protegida de acceso no autorizado por sanctum*/
Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::resource('departaments', DepartamentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::get('employeesall', [EmployeeController::class, 'all']);
    Route::get('employeesbydepartament', [EmployeeController::class, 'EmployeesByDepartament']);
    Route::post('auth/logout',[AuthController::class, 'logout']);

});



