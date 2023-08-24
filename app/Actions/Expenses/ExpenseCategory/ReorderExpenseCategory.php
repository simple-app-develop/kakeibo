<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * 品目カテゴリの並べ替えアクション
 * 
 * このクラスは、品目カテゴリの並び順を再設定するアクションを管理します。
 */
class ReorderExpenseCategory
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * ReorderExpenseCategory コンストラクタ
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
     * 品目カテゴリの並び順を更新する
     *
     * 提供された$order配列に基づいて、品目カテゴリの並び順を更新します。
     * 各カテゴリの権限を確認して、無効または無許可のデータが提供された場合は例外をスローします。
     *
     * @param array $order 並べ替えの順番を示すIDの配列
     * @throws AuthorizationException 権限がない場合に例外をスローします。
     * @return array 状態('status')とメッセージ('message')を含む配列
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
            // 各カテゴリの権限を確認する
            $isPermission = $this->expensePermissionService->checkPermission('category', 'create', $id);
            if (!$isPermission) {
                throw new AuthorizationException('You do not have permission to reorder categories for this team.');
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
