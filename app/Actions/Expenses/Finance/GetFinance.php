<?php

namespace App\Actions\Expenses\Finance;

use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class GetFinance
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * GetFinance コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }


    public function index()
    {
        // 権限を確認する
        $permissions = [
            'canUpdate' => $this->expensePermissionService->checkPermission('category', 'update'),
            'canDelete' => $this->expensePermissionService->checkPermission('category', 'delete'),
            'canCreate' => $this->expensePermissionService->checkPermission('category', 'create')
        ];

        $teamId = auth()->user()->currentTeam->id;

        $settingCounts = [
            'categoriesCount' => ExpenseCategory::where('team_id', $teamId)->count(),
            'paymentMethodsCount' => PaymentMethod::where('team_id', $teamId)->count(),
            'walletsCount' => Wallet::where('team_id', $teamId)->count(),
        ];

        return [
            'permissions' => $permissions,
            'settingCounts' => $settingCounts
        ];
    }
}
