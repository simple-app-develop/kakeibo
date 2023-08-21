<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;

class EditExpenseCategory
{
    protected $expenseCategoryService;

    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
    }

    /**
     * カテゴリの取得
     *
     * @param int $id カテゴリID
     * @param int $teamId Team ID
     * @return \App\Models\ExpenseCategory
     */
    public function get(int $id, int $teamId): ExpenseCategory
    {
        // Check permission before fetching the category
        if (!$this->expenseCategoryService->checkPermission($id, $teamId)) {
            throw new \Exception('Access forbidden. You do not have permission to edit this category.');
        }

        return ExpenseCategory::findOrFail($id);
    }
}
