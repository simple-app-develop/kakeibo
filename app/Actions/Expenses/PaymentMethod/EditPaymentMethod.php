<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;


class EditPaymentMethod
{

    protected $expensePermissionService;

    /**
     * EditExpenseCategory コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }


    public function get(int $id, int $teamId): PaymentMethod
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod', $id)) {
            throw new \Exception('This team is not authorized to edit payment methods.');
        }

        return PaymentMethod::findOrFail($id);
    }
}
