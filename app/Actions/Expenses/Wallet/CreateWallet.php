<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Support\Facades\Auth;

class CreateWallet
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

    public function create()
    {
        $isPermission = $this->expensePermissionService->checkPermission('wallet', 'create');

        if (!$isPermission) {
            return redirect()->route('wallet.index')->with('failure', 'You do not have permission to edit this wallet.');
        }

        return view('expenses.wallet.create');
    }
}
