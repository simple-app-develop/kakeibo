<?php

namespace App\Actions\Expenses\Finance;

use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Illuminate\Support\Facades\Auth;

/**
 * 家計簿データ作成アクション
 * 
 * このクラスは家計簿データの作成に関連するアクションを管理します。
 */
class CreateFinance
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * CreateFinance コンストラクタ
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
     * 家計簿データ作成ビュー用のデータを返す
     *
     * ログインしているユーザーの現在のチームに基づいて支払方法、支出カテゴリ、収入カテゴリを取得し、
     * ビューで使用するためのデータ配列として返します。
     *
     * @return array 家計簿データ作成ビューで使用するデータの配列
     */
    public function create()
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('finance', 'create');
        if (!$isPermission) {
            throw new \Exception('This team is not authorized to create household data.');
        }

        $currentTeamId = Auth::user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        // 財布を取得
        $wallets = Wallet::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();

        return [
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
            'wallets' => $wallets,
        ];
    }
}
