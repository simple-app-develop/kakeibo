<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;

class DeletePaymentMethod
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * DeletePaymentMethod コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function delete($id)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod')) {
            throw new \Exception('This team does not have the authority to remove payment methods.');
        }

        $method = PaymentMethod::findOrFail($id);
        $method->delete();
    }
}
