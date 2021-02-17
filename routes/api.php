<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ClientController;
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

Route::group(['prefix' => '{user}', 'as' => 'api.'], function ($router) {
    $router->post('/login', [AuthController::class, 'login'])->name('login');
    $router->get('/me', [AuthController::class, 'me'])->name('me');
});

Route::group(['prefix' => 'trainers', 'as' => 'api.trainers.'], function ($router) {
    $router->get('/all', [TrainerController::class, 'all'])->name('all');
    $router->get('/sessions', [TrainerController::class, 'mySessions'])->name('my-session');
    $router->post('/sessions', [TrainerController::class, 'createSession'])->name('create-session');
    $router->get('/sessions/{id}', [TrainerController::class, 'session'])->name('session');
    $router->post('/sessions/{id}/cancel', [TrainerController::class, 'cancelSession'])->name('cancel');
    $router->get('/{id}/sessions', [TrainerController::class, 'sessions'])->name('sessions');
    $router->get('/{id}/free', [TrainerController::class, 'free'])->name('free');
});

Route::group(['prefix' => 'clients', 'as' => 'api.clients.'], function ($router) {
    $router->get('/sessions', [ClientController::class, 'sessions'])->name('sessions');
    $router->post('/sessions', [ClientController::class, 'bookSession'])->name('create-session');
    $router->post('/sessions/{id}/cancel', [ClientController::class, 'cancelSession'])->name('cancel');
});

