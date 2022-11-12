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
Route::post('/users', [App\Http\Controllers\UsersController::class, 'users'])->name('users');
Route::post('/edit_users', [App\Http\Controllers\UsersController::class, 'edit_users'])->name('edit_users');
Route::post('/delete_users', [App\Http\Controllers\UsersController::class, 'delete_users'])->name('delete_users');

Route::post('/register', 'App\Http\Controllers\UsersController@register')->name('register');
Route::post('/check_card', 'App\Http\Controllers\UsersController@check_card')->name('check_card');

Route::post('/verification', 'App\Http\Controllers\VerificationsController@verif')->name('verif');
Route::post('/check_verif', 'App\Http\Controllers\VerificationsController@check_verif')->name('check_verif');
Route::post('/start_verif', 'App\Http\Controllers\VerificationsController@start_verif')->name('start_verif');
Route::post('/transaction', 'App\Http\Controllers\TransactionsController@transaction')->name('transaction');
Route::post('/check_saldo', 'App\Http\Controllers\TransactionsController@check_saldo')->name('check_saldo');
Route::post('/mutasi', 'App\Http\Controllers\TransactionsController@mutasi')->name('mutasi');

