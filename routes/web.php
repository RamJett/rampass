<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretController;
use App\Http\Controllers\AboutController;

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


Route::get('/', [SecretController::class, 'create'])->name('secret.create');
Route::post('/', [SecretController::class, 'store'])->name('secret.store');
Route::get('/{uuid}', [SecretController::class, 'show'])->name('secret.show');
Route::delete('/{uuid}', [SecretController::class, 'destroy'])->name('secret.delete');
Route::get('/page/about', [AboutController::class, 'index'])->name('about.index');
