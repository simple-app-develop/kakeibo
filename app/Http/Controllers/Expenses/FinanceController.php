<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\Finance\CreateFinance;
use App\Actions\Expenses\Finance\DeleteFinance;
use App\Actions\Expenses\Finance\EditFinance;
use App\Actions\Expenses\Finance\StoreFinance;
use App\Actions\Expenses\Finance\UpdateFinance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\FinanceStoreRequest;
use App\Http\Requests\Expenses\FinanceUpdateRequest;
use App\Models\Expense;

class FinanceController extends Controller
{
    public function index()
    {

        return view('expenses.finance.index');
    }

    public function create(CreateFinance $createView)
    {
        return view('expenses.finance.create', $createView->run());
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




    public function edit(Expense $finance, EditFinance $editView)
    {
        return view('expenses.finance.edit', $editView->run($finance));
    }



    public function destroy(Expense $finance, DeleteFinance $deleteAction)
    {
        $deleteAction->run($finance);
        return redirect()->route('finance.index')->with('success', '家計簿データが削除されました。');
    }
}
