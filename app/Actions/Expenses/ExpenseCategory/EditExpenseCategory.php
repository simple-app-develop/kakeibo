<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;

/**
 * 品目カテゴリ編集アクション
 *
 * このクラスは品目カテゴリの編集に関連するアクションを管理します。
 */
class EditExpenseCategory
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
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

    /**
     * カテゴリの取得
     *
     * 指定されたIDとチームIDに基づいて品目カテゴリを取得します。
     * 権限がない場合、例外がスローされます。
     *
     * @param int $id カテゴリID
     * @param int $teamId Team ID
     * @throws \Exception 権限がない場合に例外をスローします。
     * @return \App\Models\ExpenseCategory 取得された品目カテゴリ
     */
    public function get(int $id, int $teamId): ExpenseCategory
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('category', $id)) {
            throw new \Exception('You do not have permission to edit categories on this team.');
        }

        return ExpenseCategory::findOrFail($id);
    }
}
