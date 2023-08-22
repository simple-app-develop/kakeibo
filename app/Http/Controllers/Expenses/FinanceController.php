<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $teamId = auth()->user()->currentTeam->id;

        $validatedData = $request->validate([
            'transaction_type' => 'required|in:expense,income',
            'payment_method' => [
                'required_if:transaction_type,expense',
                Rule::exists('payment_methods', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'category' => [
                'required',
                Rule::exists('expense_categories', 'id')->where(function ($query) use ($teamId, $request) {
                    $query->where('team_id', $teamId)
                        ->where('type', $request->transaction_type);
                }),
            ],
            'amount' => 'required|numeric|between:0,99999999',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);


        $financeData = [
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
            'expense_category_id' => $validatedData['category'],
            'amount' => $validatedData['amount'],
            'description' => $validatedData['description'] ?? null,
            'date' => $validatedData['date'],
        ];

        if ($validatedData['transaction_type'] === 'expense') {
            $financeData['payment_method_id'] = $validatedData['payment_method'];
            // reflected_dateの計算ロジックをここに追加する
        }

        // 保存処理
        $finance = Expense::create($financeData);

        // return redirect()->route('finance.index')->with('success', '家計簿のデータが正常に登録されました！');
    }
}
