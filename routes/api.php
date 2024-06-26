<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SecretController;

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

Route::post('/', [SecretController::class, 'store'])->name('api.secret.store');
Route::get('/{uuid}', [SecretController::class, 'show'])->name(
  'api.secret.show'
);
Route::delete('/{uuid}', [SecretController::class, 'delete'])->name(
  'api.secret.delete'
);
