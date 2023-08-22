<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;

class ReorderPaymentMethod
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * ReorderPaymentMethod コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }
    public function reorder(array $order)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod')) {
            throw new \Exception('You do not have the authority to sort payment methods for this team.');
        }

        foreach ($order as $index => $id) {
            $method = PaymentMethod::findOrFail($id);
            $method->order_column = $index;
            $method->save();
        }
    }
}
