<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * 家計簿データ保存アクション
 * 
 * このクラスは家計簿データの保存に関連するアクションを管理します。
 */
class StoreFinance
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * StoreFinance コンストラクタ
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
     * 提供されたデータをもとに新しい家計簿データのエントリーをデータベースに保存します。
     *
     * @param array $data 保存する家計簿データのデータ
     * @return Expense 保存された家計簿データのモデルインスタンス
     */
    public function store(array $data): Expense
    {
        // 権限を確認する
        $isPermission = $this->expensePermissionService->checkPermission('finance', 'create');
        if (!$isPermission) {
            throw new \Exception('This team is not authorized to create household data.');
        }

        $teamId = Auth::user()->currentTeam->id;
        $userId = Auth::id();

        $financeData = [
            'team_id' => $teamId,
            'user_id' => $userId,
            'expense_category_id' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
            'type' => $data['transaction_type'],
        ];

        if ($data['transaction_type'] === 'expense') {
            $financeData['payment_method_id'] = $data['payment_method'];
            $paymentMethod = PaymentMethod::find($data['payment_method']);
            $inputDate = Carbon::parse($data['date']);

            if (is_null($paymentMethod->closing_date)) {
                $financeData['reflected_date'] = $inputDate;
            } else {
                if ($inputDate->day <= $paymentMethod->closing_date) {
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset);
                } else {
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset + 1);
                }

                if ($paymentMethod->payment_date > $reflectedDate->daysInMonth) {
                    $reflectedDate->endOfMonth();
                } else {
                    $reflectedDate->day($paymentMethod->payment_date);
                }

                $financeData['reflected_date'] = $reflectedDate->startOfDay();
                $financeData['wallet_id'] = null;
            }
        } elseif ($data['transaction_type'] === 'income') {
            $financeData['wallet_id'] = $data['wallet_id'] ?? null; // null合体演算子を使用
            $financeData['reflected_date'] = Carbon::parse($data['date']);
            $financeData['payment_method_id'] = null;
        } elseif ($data['transaction_type'] === 'transfer') {
            $financeData['wallet_id'] = $data['wallet_id'];
            $financeData['target_wallet_id'] = $data['target_wallet_id'];
            $financeData['reflected_date'] = Carbon::parse($data['date']);
            $financeData['payment_method_id'] = null;
        }


        return Expense::create($financeData);
    }
}
