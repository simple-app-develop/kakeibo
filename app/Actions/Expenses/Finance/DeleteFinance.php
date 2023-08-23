<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;
use App\Services\Expenses\ExpensePermissionService;

/**
 * 家計簿データ削除アクション
 * 
 * このクラスは家計簿データの削除に関連するアクションを管理します。
 */
class DeleteFinance
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * DeleteFinance コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    /**
     * 家計簿データデータを削除する
     *
     * 指定された家計簿データのモデルインスタンスを削除します。
     *
     * @param Expense $finance 削除する家計簿データのモデルインスタンス
     * @return void
     */
    public function delete(Expense $finance)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('finance')) {
            throw new \Exception('This team is not authorized to delete household data.');
        }

        $finance->delete();
    }
}
