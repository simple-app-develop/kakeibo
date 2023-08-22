<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function create()
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->get();

        return view('expenses.finance.create', [
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }


    public function store(Request $request)
    {
        // データ検証と保存のロジックをこちらに実装します
        // ...

        return redirect()->route('finance.index')->with('success', '家計簿のデータが正常に登録されました！');
    }
}
