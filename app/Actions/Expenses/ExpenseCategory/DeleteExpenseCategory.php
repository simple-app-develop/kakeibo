<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

class DeleteExpenseCategory
{
    public function delete(int $id)
    {
        try {
            ExpenseCategory::find($id)->delete();
            return [
                'status' => true,
                'message' => 'Category deleted successfully!'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => trans('messages.deletion_error')
            ];
        }
    }
}
