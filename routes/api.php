<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::middleware('[]')->name('api.')->group(function () {
    Route::prefix('auth')->group(function () {
        // Register
        // Route::post('register', Auth\RegisterApiController::class);

        // // Login
        // Route::post('login', Auth\LoginApiController::class);

        // // Logout
        // Route::post('logout', Auth\LogoutApiController::class);
    });
});

Route::prefix('incoming-item')->name('incoming-item.')->group(function () {
    Route::get('/', [Api\IncomingController::class, 'index'])->name('index');
    Route::post('store', [Api\IncomingController::class, 'store'])->name('store');
    Route::get('create', [Api\IncomingController::class, 'create'])->name('create');
    Route::get('show/{incoming_item}', [Api\IncomingController::class, 'show'])->name('show');
});

Route::prefix('outgoing-item')->name('outgoing-items.')->group(function () {
    Route::get('/', [Api\OutgoingController::class, 'index'])->name('index');
    Route::post('store', [Api\OutgoingController::class, 'store'])->name('store');
    Route::get('create', [Api\OutgoingController::class, 'create'])->name('create');
    Route::get('show/{outgoing_item}', [Api\OutgoingController::class, 'show'])->name('show');
});
