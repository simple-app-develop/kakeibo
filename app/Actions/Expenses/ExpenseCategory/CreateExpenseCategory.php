<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;

/**
 * 品目カテゴリ作成アクション
 * 
 * このクラスは品目カテゴリの作成に関連するアクションを管理します。
 */
class CreateExpenseCategory
{
    protected $expenseCategoryService;

    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
    }

    /**
     * 品目カテゴリを作成する
     *
     * @param array $data 作成する品目カテゴリのデータ
     * @return \App\Models\ExpenseCategory 作成された品目カテゴリモデルインスタンス
     */
    public function create(array $data)
    {
        $teamId = auth()->user()->currentTeam->id;

        // Check permission before fetching the category
        if (!$this->expenseCategoryService->checkPermission()) {
            throw new \Exception('Access forbidden. You do not have permission to edit this category.');
        }

        return ExpenseCategory::create($data);
    }
}
