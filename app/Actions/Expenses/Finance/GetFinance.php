<?php

namespace App\Actions\Expenses\Finance;

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
        $isPermission = $this->expensePermissionService->checkPermission('finance');

        return [
            'isPermission' => $isPermission
        ];
    }
}
