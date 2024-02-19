<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotifyMessageController;

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

Route::post('/chat/{id}', [NotifyMessageController::class, 'notifyCreateChat'])->name('notifyCreateChat');
Route::post('/chat/{id}/message', [NotifyMessageController::class, 'notifyReply'])->name('notifyReply');
