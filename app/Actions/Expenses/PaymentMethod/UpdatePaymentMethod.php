<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;

class UpdatePaymentMethod
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * UpdatePaymentMethod コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function update($id, array $data)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod')) {
            throw new \Exception('This team is not authorized to edit payment methods.');
        }

        $method = PaymentMethod::findOrFail($id);
        $method->update($data);
    }
}
