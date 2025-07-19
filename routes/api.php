<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketController;
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

Route::get('/events', [EventController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'index']);
