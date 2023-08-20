<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

class GetExpenseCategoriesByTeam
{
    public function getByTeam(int $teamId)
    {
        return ExpenseCategory::where('team_id', $teamId)->get();
    }
}
