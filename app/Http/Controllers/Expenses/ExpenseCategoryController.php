<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Actions\Expenses\ExpenseCategory\ReorderExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryReorderRequest;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    protected $createExpenseCategoryAction;
    protected $getExpenseCategoriesByTeamAction;
    protected $reorderExpenseCategoryAction;

    public function __construct(
        CreateExpenseCategory $createExpenseCategoryAction,
        GetExpenseCategoriesByTeam $getExpenseCategoriesByTeamAction,
        ReorderExpenseCategory $reorderExpenseCategoryAction
    ) {
        $this->createExpenseCategoryAction = $createExpenseCategoryAction;
        $this->getExpenseCategoriesByTeamAction = $getExpenseCategoriesByTeamAction;
        $this->reorderExpenseCategoryAction = $reorderExpenseCategoryAction;
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
        // 削除ロジックの実装
        // 例えば、Eloquentのモデルを使用してカテゴリを削除する
        ExpenseCategory::find($id)->delete();
        return redirect()->route('expense-category-index')->with('success', 'Category deleted successfully!');
    }


    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
