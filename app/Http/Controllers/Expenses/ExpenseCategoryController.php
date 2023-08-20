<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Actions\Expenses\ExpenseCategory\ReorderExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    protected $createExpenseCategoryAction;
    protected $getExpenseCategoriesByTeamAction;

    public function __construct(
        CreateExpenseCategory $createExpenseCategoryAction,
        GetExpenseCategoriesByTeam $getExpenseCategoriesByTeamAction
    ) {
        $this->createExpenseCategoryAction = $createExpenseCategoryAction;
        $this->getExpenseCategoriesByTeamAction = $getExpenseCategoriesByTeamAction;
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

    public function reorder(Request $request, ReorderExpenseCategory $reorderAction)
    {
        $order = $request->input('order');

        $result = $reorderAction->reorder($order);

        if ($result['status']) {
            return response()->json(['message' => $result['message']]);
        } else {
            return response()->json(['message' => $result['message']], 400);
        }
    }

    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
