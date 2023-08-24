<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\Wallet;
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


    public function get(int $id, int $teamId)
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('paymentMethod', 'update', $id);
        if (!$isPermission) {
            throw new \Exception('This team is not authorized to edit payment methods.');
        }

        $wallets = PaymentMethod::with('wallet')->findOrFail($id);
        $paymentMethod = PaymentMethod::findOrFail($id);

        return [
            'paymentMethod' => $paymentMethod,
            'wallets' => $wallets
        ];
    }
}
