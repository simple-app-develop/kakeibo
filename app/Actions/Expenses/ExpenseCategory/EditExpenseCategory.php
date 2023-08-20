<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

class EditExpenseCategory
{
    /**
     * カテゴリの取得
     *
     * @param int $id カテゴリID
     * @return \App\Models\ExpenseCategory
     */
    public function get(int $id): ExpenseCategory
    {
        return ExpenseCategory::findOrFail($id);
    }
}
