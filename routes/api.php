<?php

use App\Http\Controllers\Auth\PassportAuthController;
use App\Http\Controllers\ClassesConrtoller;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'auth',
], function () {
    Route::post('login', [PassportAuthController::class, 'login']);
    Route::post('register', [PassportAuthController::class, 'register']);

});

Route::group([
    'middleware' => ['auth:api', 'respond.json', 'cors'],
    'prefix' => 'auth',
], function () {
    Route::get('userInfo', [PassportAuthController::class, 'userInfo']);
    Route::get('logout', [PassportAuthController::class, 'logout']);
});


Route::group(
    [
        'middleware' => ['auth:api', 'respond.json', 'cors'],
    ],
    function () {
        Route::resource('student', StudentController::class);
        Route::get('/classes' , [ ClassesConrtoller::class , 'index']);
        Route::get('/classes' , [ ClassesConrtoller::class , 'index']);
        
        Route::resource('/department' ,DepartmentController::class);
    }
);
