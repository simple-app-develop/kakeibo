<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

/**
 * 品目カテゴリの並べ替えアクション
 * 
 * このクラスは、品目カテゴリの並び順を再設定するアクションを管理します。
 */
class ReorderExpenseCategory
{
    /**
     * 品目カテゴリの並び順を更新する
     *
     * @param array $order 並べ替えの順番を示すIDの配列
     * @return array 状態とメッセージを含む配列
     */
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
