<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\Finance\StoreFinance;
use App\Actions\Expenses\Finance\UpdateFinance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\FinanceStoreRequest;
use App\Http\Requests\Expenses\FinanceUpdateRequest;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;

class FinanceController extends Controller
{
    public function index()
    {

        return view('expenses.finance.index');
    }

    public function create()
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return view('expenses.finance.create', [
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }

    public function store(FinanceStoreRequest $request, StoreFinance $storeFinance)
    {
        $validatedData = $request->validated();
        $validatedData['team_id'] = auth()->user()->currentTeam->id;
        $validatedData['user_id'] = auth()->id();

        $storeFinance->run($validatedData);

        return redirect()->route('finance.index')->with('success', 'success');
    }

    public function update(FinanceUpdateRequest $request, Expense $finance, UpdateFinance $updateFinance)
    {
        $validatedData = $request->validated();
        $validatedData['team_id'] = auth()->user()->currentTeam->id;
        $validatedData['user_id'] = auth()->id();

        $updateFinance->run($finance, $validatedData);

        return redirect()->route('finance.index')->with('success', '更新に成功しました！');
    }




    public function edit(Expense $finance)
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return view('expenses.finance.edit', [
            'finance' => $finance,
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }



    public function destroy(Expense $finance)
    {
        $finance->delete();

        return redirect()->route('finance.index')->with('success', '家計簿データが削除されました。');
    }
}
