<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;

class CreatePaymentMethod
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * CreatePaymentMethod コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    public function create()
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('paymentMethod', 'create');
        if (!$isPermission) {
            throw new \Exception('This team is not authorized to create payment methods.');
        }

        $teamId = auth()->user()->currentTeam->id;
        $wallets = Wallet::where('team_id', $teamId)->orderBy('order_column', 'asc')->get();

        // 作成ビューを返す
        return view('expenses.payment_method.create', compact('wallets'));
    }

    public function store(array $data)
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('paymentMethod', 'create');
        if (!$isPermission) {
            throw new \Exception('This team is not authorized to create payment methods.');
        }

        return PaymentMethod::create($data);
    }
}
