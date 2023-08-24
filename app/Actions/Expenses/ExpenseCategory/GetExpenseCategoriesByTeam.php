<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;

/**
 * チームごとの品目カテゴリ取得アクション
 * 
 * このクラスは、特定のチームに関連する品目カテゴリの取得に関するアクションを管理します。
 */
class GetExpenseCategoriesByTeam
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * GetExpenseCategoriesByTeam コンストラクタ
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
     * 指定されたチームIDに関連する品目カテゴリを取得する
     *
     * このメソッドは、指定されたチームIDに関連する品目カテゴリをデータベースから取得し、
     * order_columnで昇順に並べ替えた結果をコレクションとして返します。
     *
     * @param int $teamId 品目カテゴリを取得するチームのID
     * @return \Illuminate\Database\Eloquent\Collection 取得された品目カテゴリのコレクション
     */
    public function getByTeam(int $teamId)
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('category', 'read');
        if (!$isPermission) {
            throw new \Exception('You are not authorized to create categories on this team.');
        }

        $permissions = [
            'canUpdate' => $this->expensePermissionService->checkPermission('category', 'update'),
            'canDelete' => $this->expensePermissionService->checkPermission('category', 'delete'),
            'canCreate' => $this->expensePermissionService->checkPermission('category', 'create')
        ];


        $categories = ExpenseCategory::where('team_id', $teamId)
            ->orderBy('order_column', 'asc')
            ->get();

        return [
            'categories' => $categories,
            'permissions' => $permissions
        ];
    }
}
