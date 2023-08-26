<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class DestroyWallet
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * CreateWallet コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function destroy(Wallet $wallet)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'delete');

        if (!$isPermission) {
            throw new \Exception('You do not have permission to delete this wallet.');
        }

        if ($wallet->paymentMethods->count() > 0 || $wallet->expenses->count() > 0) {
            throw new \Exception('Cannot delete wallet as it is associated with payment methods or finances.');
        }

        $wallet->delete();
    }
}
