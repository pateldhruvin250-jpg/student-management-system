<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes below require a valid Sanctum token (auth:sanctum),
| and write operations are further restricted to admin/staff via the
| 'role' middleware registered in bootstrap/app.php.
*/

Route::middleware('auth:sanctum')->group(function () {

    // Read access: any authenticated user (admin, staff, or student)
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{student}', [StudentController::class, 'show']);
    Route::get('/students/{student}/attendance', [AttendanceController::class, 'index']);
    Route::get('/students/{student}/grades', [GradeController::class, 'index']);

    // Write access: admin/staff only
    Route::middleware('role:admin,staff')->group(function () {
        Route::post('/students', [StudentController::class, 'store']);
        Route::put('/students/{student}', [StudentController::class, 'update']);
        Route::delete('/students/{student}', [StudentController::class, 'destroy']);

        Route::post('/students/{student}/attendance', [AttendanceController::class, 'store']);
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update']);
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy']);

        Route::post('/students/{student}/grades', [GradeController::class, 'store']);
        Route::put('/grades/{grade}', [GradeController::class, 'update']);
        Route::delete('/grades/{grade}', [GradeController::class, 'destroy']);
    });
});

Route::get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
