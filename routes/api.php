<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('leave-requests', LeaveRequestController::class);

Route::get('/attendances', [AttendanceController::class, 'index']);
Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn']);
Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut']);
