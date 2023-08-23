<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Services\Expenses\ExpensePermissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * 家計簿データ更新アクション
 * 
 * このクラスは家計簿データの更新に関連するアクションを管理します。
 */
class UpdateFinance
{
    /**
     * Permissionサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * UpdateFinance コンストラクタ
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
     * 既存の家計簿データエントリーを提供されたデータで更新します。
     *
     * @param Expense $expense 更新する家計簿データのエントリー
     * @param array $data 家計簿データデータの更新
     */
    public function update(Expense $expense, array $data)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('finance')) {
            throw new \Exception('This team is not authorized to edit household data.');
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
            $financeData['wallet_id'] = $data['wallet_id'];
            $financeData['reflected_date'] = Carbon::parse($data['date']);
            $financeData['payment_method_id'] = null;
        }

        $expense->update($financeData);
    }
}
