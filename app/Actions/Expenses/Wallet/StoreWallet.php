<?php

namespace App\Actions\Expenses\Wallet;

use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Http\Request;

class StoreWallet
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * StoreWallet コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function store(Request $request)
    {
        // 権限の確認
        if (!$this->expensePermissionService->checkPermission('wallet', 'create')) {
            throw new \Exception('You do not have permission to edit this wallet.');
        }

        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|integer|min:0',
        ]);

        // ウォレットの作成
        $wallet = new Wallet;
        $wallet->team_id = $request->user()->currentTeam->id;
        $wallet->name = $request->name;
        $wallet->balance = $request->balance;
        $wallet->save();

        return $wallet;
    }
}
