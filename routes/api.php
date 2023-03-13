<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| API Unprotected Routes
|--------------------------------------------------------------------------
*/
//Auth unprotected routes
Route::prefix('/auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login'])->name('login-attempt');
    Route::get('/logout', [AuthController::class, 'logout']);   
});

//User unprotected routes
Route::prefix('/user')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
});
//Todo List unprotected routes
Route::prefix('/todo-list')->group(function() {
});


/*
|--------------------------------------------------------------------------
| API Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    //User API routes
    Route::prefix('/user')->group(function() {
        Route::get('/read', [UserController::class, 'retrieve']);
        Route::post('/update-image', [UserController::class, 'updateImage']);
        Route::post('/update', [UserController::class, 'update']);
    });
    //Todo List API routes
    Route::prefix('/todo-list')->group(function() {
        Route::get('/read', [TodoController::class, 'read']);
        Route::get('/readDetail/{id}', [TodoController::class, 'readDetail']);
        Route::post('/insert', [TodoController::class, 'insert']);
        Route::post('/update/{id}', [TodoController::class, 'update']);
        Route::post('/delete/{id}', [TodoController::class, 'delete']);
        Route::get('/sendReminder', [TodoController::class, 'sendReminder']);
    });
    //Task API routes
    Route::prefix('/task')->group(function() {
        Route::get('/readTasks/{todo_list_id}', [TaskController::class, 'readTasks']);
        Route::post('/insert', [TaskController::class, 'insert']);
        Route::post('/delete/{id}', [TaskController::class, 'delete']);
    });
});