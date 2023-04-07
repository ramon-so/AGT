<?php

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

Route::get('transactions', 'App\Http\Controllers\TransactionController@index');
Route::post('transactions', 'App\Http\Controllers\TransactionController@store');
Route::put('transactions/{id}', 'App\Http\Controllers\TransactionController@update');
Route::delete('transactions/{id}', 'App\Http\Controllers\TransactionController@destroy');

