<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * 品目カテゴリ削除アクション
 * 
 * このクラスは品目カテゴリの削除に関連するアクションを管理します。
 */
class DeleteExpenseCategory
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * DeleteExpenseCategory コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    /**
     * 指定されたIDの品目カテゴリを削除する
     *
     * @param int $id 削除する品目カテゴリのID
     * @throws AuthorizationException 権限がない場合に例外をスローします。
     * @return array 削除操作の結果を示す連想配列。'status'キーで成功/失敗の状態を、'message'キーで結果メッセージを示す。
     */
    public function delete(int $id): array
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('category', 'delete', $id);
        if (!$isPermission) {
            throw new AuthorizationException('You do not have permission to delete categories for this team.');
        }

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
