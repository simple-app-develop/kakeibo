<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

class ReorderExpenseCategory
{
    public function reorder($order)
    {
        if (!is_array($order) || empty($order)) {
            return [
                'status' => false,
                'message' => 'Invalid order data provided'
            ];
        }

        // リクエストから送られてきた順序に従ってorder_columnを更新
        foreach ($order as $index => $id) {
            $category = ExpenseCategory::find($id);
            if ($category) {
                $category->order_column = $index;
                $category->save();
            }
        }

        return [
            'status' => true,
            'message' => 'Order updated successfully'
        ];
    }
}
