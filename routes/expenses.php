<?php

use App\Http\Controllers\Expenses\ExpenseCategoryController;
use App\Http\Controllers\Expenses\FinanceController;
use App\Http\Controllers\Expenses\PaymentMethodController;
use App\Http\Controllers\Expenses\WalletController;
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

    // 支払い方法の一覧表示
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-method.index');

    // 支払い方法の新規登録フォーム表示
    Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-method.create');

    // 支払い方法の保存
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-method.store');

    // 支払い方法の編集フォーム表示
    Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])->name('payment-method.edit');

    // 支払い方法の更新
    Route::patch('/payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('payment-method.update');

    // 支払い方法の削除
    Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])->name('payment-method.destroy');

    // 支払い方法の並び替え
    Route::post('/payment-methods/reorder', [PaymentMethodController::class, 'reorder'])->name('payment-method.reorder');


    Route::get('/finances', [FinanceController::class, 'index'])->name('finance.index');

    // 家計簿のデータの新規登録フォーム表示
    Route::get('/finance/create', [FinanceController::class, 'create'])->name('finance.create');

    // 家計簿のデータの保存
    Route::post('/finance/store', [FinanceController::class, 'store'])->name('finance.store');

    // routes/web.php
    Route::get('/finance/{finance}/edit', [FinanceController::class, 'edit'])->name('finance.edit');
    Route::put('/finance/{finance}', [FinanceController::class, 'update'])->name('finance.update');

    Route::delete('/finance/{finance}', [FinanceController::class, 'destroy'])->name('finance.destroy');

    Route::get('/wallet/create', [WalletController::class, 'create'])->name('wallet.create');
    Route::post('/wallet/store', [WalletController::class, 'store'])->name('wallet.store');
});
