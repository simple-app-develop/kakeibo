<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class FetchWallets
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

    public function fetch($teamId)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'read');

        if (!$isPermission) {
            throw new \Exception('You do not have permission to view this wallet.');
        }

        $permissions = [
            'canUpdate' => $this->expensePermissionService->checkPermission('wallet', 'update'),
            'canDelete' => $this->expensePermissionService->checkPermission('wallet', 'delete'),
            'canCreate' => $this->expensePermissionService->checkPermission('wallet', 'create')
        ];

        $wallets = Wallet::where('team_id', $teamId)->orderBy('order_column', 'asc')->get();

        return compact('wallets', 'permissions');
    }
}
