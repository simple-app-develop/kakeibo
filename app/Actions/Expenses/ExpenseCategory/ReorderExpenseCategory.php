<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;

/**
 * 品目カテゴリの並べ替えアクション
 * 
 * このクラスは、品目カテゴリの並び順を再設定するアクションを管理します。
 */
class ReorderExpenseCategory
{
    protected $expenseCategoryService;

    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
    }

    /**
     * 品目カテゴリの並び順を更新する
     *
     * @param array $order 並べ替えの順番を示すIDの配列
     * @return array 状態とメッセージを含む配列
     */
    public function reorder(array $order)
    {
        $teamId = auth()->user()->currentTeam->id;

        if (!is_array($order) || empty($order)) {
            return [
                'status' => false,
                'message' => 'Invalid order data provided'
            ];
        }

        foreach ($order as $index => $id) {
            // Check permission for each category
            if (!$this->expenseCategoryService->checkPermission($id, $teamId)) {
                return [
                    'status' => false,
                    'message' => 'Access forbidden. You do not have permission to reorder some categories.'
                ];
            }

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
