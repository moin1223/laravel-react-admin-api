<?php
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/orders/test', [OrderController::class, 'testOrder']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('/users', UserController::class);
    Route::post('/users/roles', [UserController::class, 'assignRole']);
    Route::post('/users/roles/user-role', [UserController::class, 'userRole']);
    Route::post('/users/roles/delete', [UserController::class, 'removeRole']);
    
    
    Route::resource('/roles', RoleController::class);

    Route::resource('/permissions', PermissionController::class);  
    Route::post('/roles/permissions', [RoleController::class, 'givePermission']);
    Route::post('/roles/permissions/delete', [RoleController::class, 'revokePermission']);

    Route::resource('/orders', OrderController::class);
    Route::post('/orders/details', [OrderController::class, 'orderDetails']);

   
});
