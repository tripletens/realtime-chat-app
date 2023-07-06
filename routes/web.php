<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // chat page
    Route::get('/chat/{code}', [MessageController::class, 'index'])->name('chat.index');
    Route::get('/join-chat', [MessageController::class, 'join'])->name('chat.join');
    Route::post('/send-message', [MessageController::class, 'sendMessage']);

    Route::post('/typing', [MessageController::class, 'typing']);
    Route::post('/stop-typing', [MessageController::class, 'stopTyping']);

    // create chat page
    Route::get('/create-chat', [ChatController::class, 'create_chat'])->name('chat.create');
    Route::post('/save-chat', [ChatController::class, 'save_chat'])->name('chat.save');
    Route::post('/save-join-chat', [ChatController::class, 'join_chat'])->name('chat.process.join');
    Route::post('/leave-chat', [ChatController::class, 'leave_chat'])->name('chat.process.leave');
    Route::post('/end-chat', [ChatController::class, 'end_chat'])->name('chat.process.end');
});

require __DIR__.'/auth.php';
