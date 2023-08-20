<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\DeleteExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Actions\Expenses\ExpenseCategory\ReorderExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\UpdateExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryReorderRequest;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use App\Http\Requests\Expenses\ExpenseCategoryUpdateRequest;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    protected $createExpenseCategoryAction;
    protected $getExpenseCategoriesByTeamAction;
    protected $reorderExpenseCategoryAction;
    protected $updateExpenseCategoryAction;
    protected $deleteExpenseCategoryAction;

    public function __construct(
        CreateExpenseCategory $createExpenseCategoryAction,
        GetExpenseCategoriesByTeam $getExpenseCategoriesByTeamAction,
        ReorderExpenseCategory $reorderExpenseCategoryAction,
        UpdateExpenseCategory $updateExpenseCategoryAction,
        DeleteExpenseCategory $deleteExpenseCategoryAction,
    ) {
        $this->createExpenseCategoryAction = $createExpenseCategoryAction;
        $this->getExpenseCategoriesByTeamAction = $getExpenseCategoriesByTeamAction;
        $this->reorderExpenseCategoryAction = $reorderExpenseCategoryAction;
        $this->updateExpenseCategoryAction = $updateExpenseCategoryAction;
        $this->deleteExpenseCategoryAction = $deleteExpenseCategoryAction;
    }

    public function create()
    {
        return view('expenses.expense_categories.create');
    }

    public function store(ExpenseCategoryStoreRequest $request)
    {
        $data = $request->all();
        $data['team_id'] = $this->getCurrentTeamId();

        $this->createExpenseCategoryAction->create($data);

        return redirect()->route('expense-category-index')->with('success', 'Category created successfully!');
    }

    public function index()
    {
        $categories = $this->getExpenseCategoriesByTeamAction->getByTeam($this->getCurrentTeamId());
        return view('expenses.expense_categories.index', compact('categories'));
    }

    public function reorder(ExpenseCategoryReorderRequest $request)
    {
        $order = $request->input('order');

        $result = $this->reorderExpenseCategoryAction->reorder($order);

        if ($result['status']) {
            return response()->json(['message' => $result['message']]);
        } else {
            return response()->json(['message' => $result['message']], 400);
        }
    }

    public function destroy($id)
    {
        $result = $this->deleteExpenseCategoryAction->delete($id);

        if ($result['status']) {
            return redirect()->route('expense-category-index')->with('success', $result['message']);
        } else {
            return redirect()->route('expense-category-index')->withErrors(['error' => $result['message']]);
        }
    }

    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        return view('expenses.expense_categories.edit', compact('category'));
    }

    public function update(ExpenseCategoryUpdateRequest $request, $id)
    {
        $result = $this->updateExpenseCategoryAction->update($id, $request->all());

        if ($result['status']) {
            return redirect()->route('expense-category-index')->with('success', $result['message']);
        } elseif ($result['error'] === 'unique_constraint') {
            return redirect()->back()->withErrors(['name' => $result['message']])->withInput();
        } else {
            return redirect()->back()->withErrors(['error' => $result['message']])->withInput();
        }
    }

    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
