<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/{user}/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('user.transactions');
Route::get('/user/{user}/deposit', [App\Http\Controllers\TransactionController::class, 'deposit'])->name('user.deposit');
Route::get('/user/{user}/withdraw', [App\Http\Controllers\TransactionController::class, 'withdraw'])->name('user.withdraw');
Route::post('/user/createdeposit', [App\Http\Controllers\TransactionController::class, 'createDeposit'])->name('user.createdeposit');
Route::post('/user/createwithdraw', [App\Http\Controllers\TransactionController::class, 'createWithdraw'])->name('user.createwithdraw');
