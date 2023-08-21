<?php

use App\Http\Controllers\Expenses\ExpenseCategoryController;
use App\Http\Controllers\Expenses\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/expense-category/create', [ExpenseCategoryController::class, 'create'])->name('expense-category-create');
    Route::post('/expense-category/store', [ExpenseCategoryController::class, 'store'])->name('expense-category-store');

    Route::get('/expense-categories/{id}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-category-edit');
    Route::patch('/expense-categories/{id}', [ExpenseCategoryController::class, 'update'])->name('expense-category-update');

    Route::delete('/expense-category/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expense-category-destroy');

    Route::get('/expense-category/', [ExpenseCategoryController::class, 'index'])->name('expense-category-index');
    Route::post('/expense-category/reorder', [ExpenseCategoryController::class, 'reorder'])->name('expense-category-reorder');

    Route::get('/payment-method/create', [PaymentMethodController::class, 'create'])->name('payment-method-create');
    Route::post('/payment-method/store', [PaymentMethodController::class, 'store'])->name('payment-method-store');
});
