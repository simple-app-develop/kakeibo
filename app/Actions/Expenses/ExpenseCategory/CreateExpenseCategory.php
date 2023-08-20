<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

class CreateExpenseCategory
{
    public function create(array $data)
    {
        return ExpenseCategory::create($data);
    }
}
