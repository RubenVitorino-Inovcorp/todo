<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TaskController::class);
});

require __DIR__.'/settings.php';
