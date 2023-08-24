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
        $isPermission = $this->expensePermissionService->checkPermission('paymentMethod', 'delete');
        if (!$isPermission) {
            throw new \Exception('This team does not have the authority to remove payment methods.');
        }

        $method = PaymentMethod::findOrFail($id);

        // ここで支払い方法が使用されているかどうかを確認します。
        if ($method->expenses->count() > 0) {
            throw new \Exception('This payment method is already being used and cannot be deleted.');
        }


        $method->delete();
    }
}
