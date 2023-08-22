<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;

class GetPaymentMethods
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * GetPaymentMethods コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function getByTeam($teamId)
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('paymentMethod');

        $paymentMethods = PaymentMethod::where('team_id', $teamId)
            ->orderBy('order_column', 'asc')
            ->get();

        return [
            'paymentMethods' => $paymentMethods,
            'isPermission' => $isPermission
        ];
    }
}