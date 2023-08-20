<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

/**
 * 品目カテゴリ削除アクション
 * 
 * このクラスは品目カテゴリの削除に関連するアクションを管理します。
 */
class DeleteExpenseCategory
{
    /**
     * 指定されたIDの品目カテゴリを削除する
     *
     * @param int $id 削除する品目カテゴリのID
     * @return array 削除操作の結果を示す連想配列。'status'キーで成功/失敗の状態を、'message'キーで結果メッセージを示す。
     */
    public function delete(int $id): array
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
