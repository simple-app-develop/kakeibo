<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpenseCategoryService;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * 品目カテゴリ削除アクション
 * 
 * このクラスは品目カテゴリの削除に関連するアクションを管理します。
 */
class DeleteExpenseCategory
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpenseCategoryService
     */
    protected $expenseCategoryService;

    /**
     * DeleteExpenseCategory コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpenseCategoryService $expenseCategoryService 品目カテゴリサービス
     */
    public function __construct(ExpenseCategoryService $expenseCategoryService)
    {
        $this->expenseCategoryService = $expenseCategoryService;
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
        $teamId = auth()->user()->currentTeam->id;

        // 権限を確認する
        if (!$this->expenseCategoryService->checkPermission($id, $teamId)) {
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
