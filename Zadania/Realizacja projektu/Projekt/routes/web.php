<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Vehicles\ShowVehicleController;
use App\Http\Controllers\Vehicles\VehicleController;

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

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'loginAuthenticate'])->name('auth.login.loginAuthenticate');
Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/register', [AuthController::class, 'registerValidate'])->name('auth.register.registerValidate');
Route::get('/logout', [AuthController::class, 'redirectToHome']);
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::get('/account/topUp', [AccountController::class, 'redirectToHome']);
Route::post('/account/topUp', [AccountController::class, 'topUp'])->name('account.topUp');
Route::get('/account/edit', [AuthController::class, 'edit'])->name('auth.edit');
Route::post('/account/edit', [AuthController::class, 'editValidate'])->name('auth.edit.editValidate');

Route::get('/vehicles/show', [ShowVehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vehicles/show-service', [ShowVehicleController::class, 'showService'])->name('vehicles.showService');

Route::get('/vehicles/send-to-service/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/send-to-service/{id}', [VehicleController::class, 'sendToService'])->name('vehicles.sendToService');
Route::get('/vehicles/end-service/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/end-service/{id}', [VehicleController::class, 'endService'])->name('vehicles.endService');
Route::get('/vehicles/start-sale/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/start-sale/{id}', [VehicleController::class, 'startSale'])->name('vehicles.startSale');
Route::get('/vehicles/end-sale/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/end-sale/{id}', [VehicleController::class, 'endSale'])->name('vehicles.endSale');
Route::get('/vehicles/delete/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/delete/{id}', [VehicleController::class, 'delete'])->name('vehicles.delete');
Route::get('/vehicles/purchase/{id}', [VehicleController::class, 'redirectToHome']);
Route::post('/vehicles/purchase/{id}', [VehicleController::class, 'purchase'])->name('vehicles.purchase');

Route::get('/vehicles/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::post('/vehicles/edit', [VehicleController::class, 'editValidate'])->name('vehicles.edit.editValidate');

Route::get('/vehicles/register', [VehicleController::class, 'register'])->name('vehicles.register');
Route::post('/vehicles/register', [VehicleController::class, 'registerValidate'])->name('vehicles.register.registerValidate');
