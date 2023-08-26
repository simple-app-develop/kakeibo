<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Http\Request;

class ReorderWallet
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

    public function reorder(Request $request)
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'create');

        if (!$isPermission) {
            throw new \Exception('You do not have the authority to sort wallets for this team.');
        }

        $order = $request->input('order');

        foreach ($order as $index => $id) {
            $wallet = Wallet::findOrFail($id);
            $wallet->order_column = $index;
            $wallet->save();
        }
    }
}
