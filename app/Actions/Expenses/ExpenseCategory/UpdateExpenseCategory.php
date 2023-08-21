<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;
use Illuminate\Database\QueryException;

/**
 * 品目カテゴリの更新アクション
 * 
 * このクラスは、品目カテゴリの更新アクションを管理します。
 */
class UpdateExpenseCategory
{
    protected $expenseCategoryService;

    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
    }

    /**
     * 指定されたIDの品目カテゴリを更新します。
     * 
     * このメソッドは、指定されたIDの品目カテゴリを探し、与えられたデータで更新を試みます。
     * 一意性制約違反やその他のデータベースエラーをハンドリングします。
     *
     * @param int   $id   更新対象の品目カテゴリID
     * @param array $data 更新データ
     * 
     * @return array 状態、エラーコード（存在する場合）、およびメッセージを含む配列
     */
    public function update(int $id, array $data, int $teamId)
    {
        // Check permission before updating the category
        if (!$this->expenseCategoryService->checkPermission($id, $teamId)) {
            throw new \Exception('Access forbidden. You do not have permission to edit this category.');
        }


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
