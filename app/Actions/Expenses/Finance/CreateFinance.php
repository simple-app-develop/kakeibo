<?php

namespace App\Actions\Expenses\Finance;

use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

/**
 * 家計簿データ作成アクション
 * 
 * このクラスは家計簿データの作成に関連するアクションを管理します。
 */
class CreateFinance
{
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
        $currentTeamId = Auth::user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return [
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ];
    }
}
