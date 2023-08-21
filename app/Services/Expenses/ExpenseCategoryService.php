<?php

namespace App\Services\Expenses;

use App\Models\ExpenseCategory;

class ExpenseCategoryService
{
    /**
     * Check if the user has permission to edit the category
     *
     * @param int $id Category ID
     * @param int $teamId Team ID
     * @return bool
     */
    public function checkPermission(int $id, int $teamId): bool
    {
        return ExpenseCategory::where('id', $id)
            ->where('team_id', $teamId)
            ->exists();
    }
}
