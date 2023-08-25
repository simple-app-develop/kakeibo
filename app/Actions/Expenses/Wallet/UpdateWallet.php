<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Http\Request;

class UpdateWallet
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

    public function update(Request $request, Wallet $wallet)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'update');

        if (!$isPermission) {
            throw new \Exception('You do not have permission to update this wallet.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $wallet->name = $request->name;
        $wallet->save();

        return $wallet;
    }
}
