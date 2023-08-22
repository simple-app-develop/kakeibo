<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;

class CreatePaymentMethod
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * CreatePaymentMethod コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function create()
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod')) {
            throw new \Exception('This team is not authorized to create payment methods.');
        }

        // 作成ビューを返す
        return view('expenses.payment_method.create');
    }

    public function store(array $data)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod')) {
            throw new \Exception('This team is not authorized to create payment methods.');
        }

        return PaymentMethod::create($data);
    }
}