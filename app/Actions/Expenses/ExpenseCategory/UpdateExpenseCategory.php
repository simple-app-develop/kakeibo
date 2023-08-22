<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Database\QueryException;

/**
 * 品目カテゴリの更新アクション
 * 
 * このクラスは、品目カテゴリの更新アクションを管理します。
 */
class UpdateExpenseCategory
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * UpdateExpenseCategory コンストラクタ
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
     * 指定されたIDの品目カテゴリを更新します。
     *
     * このメソッドは、指定されたIDの品目カテゴリを探し、与えられたデータで更新を試みます。
     * 一意性制約違反やその他のデータベースエラーをハンドリングします。
     *
     * @param int   $id     更新対象の品目カテゴリID
     * @param array $data   更新データ
     * @param int   $teamId チームID
     * @throws \Exception 権限がない場合に例外をスローします。
     * @return array 状態、エラーコード（存在する場合）、およびメッセージを含む配列
     */
    public function update(int $id, array $data, int $teamId)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('paymentMethod', $id)) {
            throw new \Exception('You do not have permission to edit categories on this team.');
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
