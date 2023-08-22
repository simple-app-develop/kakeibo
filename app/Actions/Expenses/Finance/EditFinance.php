<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;

/**
 * 家計簿データ編集アクション
 * 
 * このクラスは家計簿データの編集に関連するアクションを管理します。
 */
class EditFinance
{
    /**
     * 家計簿データを編集するための準備を行い、関連データを取得します。
     *
     * 現在のチームIDを使用して、関連する支払い方法、支出カテゴリ、収入カテゴリのリストを取得します。
     * これらのデータと編集対象の家計簿データを配列として返します。
     *
     * @param Expense $finance 編集する家計簿データのモデルインスタンス
     * @return array 関連データを含む配列
     */
    public function edit(Expense $finance)
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return [
            'finance' => $finance,
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ];
    }
}
