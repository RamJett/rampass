<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretController;

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

Route::get('/', [SecretController::class, 'index'])->name('home');
Route::post('/store', [SecretController::class, 'store'])->name('secret.store');
