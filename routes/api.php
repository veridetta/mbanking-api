<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [App\Http\Controllers\UsersController::class, 'login'])->name('login');
Route::get('/users', [App\Http\Controllers\UsersController::class, 'users'])->name('users');
Route::post('/register', 'App\Http\Controllers\UsersController@register')->name('register');
Route::post('/verification', 'App\Http\Controllers\VerificationsController@verif')->name('verif');
Route::post('/start_verif', 'App\Http\Controllers\VerificationsController@start')->name('start_verif');
Route::post('/transaction', 'App\Http\Controllers\TransactionsController@transaction')->name('transaction');


