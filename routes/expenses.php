<?php

use App\Http\Controllers\Expenses\ExpenseCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/expense-category/create', [ExpenseCategoryController::class, 'create'])->name('expense-category-create');
    Route::post('/expense-category/store', [ExpenseCategoryController::class, 'store'])->name('expense-category-store');

    Route::get('/expense-category/', [ExpenseCategoryController::class, 'index'])->name('expense-category-index');
    Route::post('/expense-category/reorder', [ExpenseCategoryController::class, 'reorder'])->name('expense-category-reorder');
});
