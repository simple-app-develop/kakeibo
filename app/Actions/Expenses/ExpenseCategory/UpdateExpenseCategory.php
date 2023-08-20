<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use Illuminate\Database\QueryException;

class UpdateExpenseCategory
{
    public function update(int $id, array $data)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            $category->update($data);

            return [
                'status' => true,
                'message' => 'Category updated successfully!'
            ];
        } catch (QueryException $e) {
            // 一意性制約違反のエラーコードは "23000" です。
            if ($e->getCode() === "23000") {
                return [
                    'status' => false,
                    'error' => 'unique_constraint',
                    'message' => trans('messages.category_name_taken')
                ];
            }
            return [
                'status' => false,
                'error' => 'database_error',
                'message' => trans('messages.db_error')
            ];
        }
    }
}
