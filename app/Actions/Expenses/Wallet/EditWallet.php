<?php

namespace App\Actions\Expenses\Wallet;

use App\Services\Expenses\ExpensePermissionService;

class EditWallet
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

    public function edit()
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'update');

        if (!$isPermission) {
            throw new \Exception('You do not have permission to update this wallet.');
        }
    }
}
