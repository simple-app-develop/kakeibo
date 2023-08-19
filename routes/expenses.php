<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/expense-categories', function () {
        return view('expenses.categories');
    })->name('expense-categories');
});
