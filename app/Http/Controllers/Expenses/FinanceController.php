<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\Finance\CreateFinance;
use App\Actions\Expenses\Finance\DeleteFinance;
use App\Actions\Expenses\Finance\EditFinance;
use App\Actions\Expenses\Finance\GetFinance;
use App\Actions\Expenses\Finance\StoreFinance;
use App\Actions\Expenses\Finance\UpdateFinance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\FinanceStoreRequest;
use App\Http\Requests\Expenses\FinanceUpdateRequest;
use App\Models\Expense;

/**
 * 家計簿のデータを扱うコントローラー
 */
class FinanceController extends Controller
{
    /**
     * 家計簿のデータの一覧を表示
     *
     * @return \Illuminate\View\View
     */
    public function index(GetFinance $getFinance)
    {
        $result = $getFinance->index();

        return view('expenses.finance.index', [
            'permissions' => $result['permissions']
        ]);
    }


    /**
     * 家計簿のデータの作成画面を表示
     *
     * @param CreateFinance $createView
     * @return \Illuminate\View\View
     */
    public function create(CreateFinance $createView)
    {
        try {
            return view('expenses.finance.create', $createView->create());
        } catch (\Exception $e) {
            return redirect()->route('finance.index')->with('failure', $e->getMessage());
        }
    }

    /**
     * 家計簿のデータを保存
     *
     * @param FinanceStoreRequest $request
     * @param StoreFinance $storeFinance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FinanceStoreRequest $request, StoreFinance $storeFinance)
    {
        $validatedData = $request->validated();
        try {
            $storeFinance->store($validatedData);
        } catch (\Exception $e) {
            return redirect()->route('finance.index')->with('failure', $e->getMessage());
        }

        return redirect()->route('finance.index')->with('success', 'Saved household data.');
    }

    /**
     * 家計簿のデータの編集画面を表示
     *
     * @param Expense $finance
     * @param EditFinance $editView
     * @return \Illuminate\View\View
     */
    public function edit(Expense $finance, EditFinance $editView)
    {
        try {
            return view('expenses.finance.edit', $editView->edit($finance));
        } catch (\Exception $e) {
            return redirect()->route('finance.index')->with('failure', $e->getMessage());
        }
    }

    /**
     * 家計簿のデータを更新
     *
     * @param FinanceUpdateRequest $request
     * @param Expense $finance
     * @param UpdateFinance $updateFinance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FinanceUpdateRequest $request, Expense $finance, UpdateFinance $updateFinance)
    {
        $validatedData = $request->validated();
        try {
            $updateFinance->update($finance, $validatedData);
        } catch (\Exception $e) {
            return redirect()->route('finance.index')->with('failure', $e->getMessage());
        }

        return redirect()->route('finance.index')->with('success', 'Edited household data.');
    }

    /**
     * 家計簿のデータを削除
     *
     * @param Expense $finance
     * @param DeleteFinance $deleteAction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Expense $finance, DeleteFinance $deleteAction)
    {
        try {
            $deleteAction->delete($finance);
        } catch (\Exception $e) {
            return redirect()->route('finance.index')->with('failure', $e->getMessage());
        }
        return redirect()->route('finance.index')->with('success', 'Deleted household data.');
    }
}
