<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;

/**
 * 品目カテゴリ編集アクション
 *
 * このクラスは品目カテゴリの編集に関連するアクションを管理します。
 */
class EditExpenseCategory
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpenseCategoryService
     */
    protected $expenseCategoryService;

    /**
     * EditExpenseCategory コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpenseCategoryService $expenseCategoryService 品目カテゴリサービス
     */
    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
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
        if (!$this->expenseCategoryService->checkPermission($id, $teamId)) {
            throw new \Exception('Access forbidden. You do not have permission to edit categories on this team.');
        }

        return ExpenseCategory::findOrFail($id);
    }
}
