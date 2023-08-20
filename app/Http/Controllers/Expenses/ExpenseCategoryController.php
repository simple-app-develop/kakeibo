<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $data['team_id'] = $this->getCurrentTeamId();

        $this->createExpenseCategoryAction->create($data);

        return redirect()->route('expense-category-index')->with('status', 'Category created successfully!');
    }

    public function index()
    {
        $categories = $this->getExpenseCategoriesByTeamAction->getByTeam($this->getCurrentTeamId());
        return view('expenses.expense_categories.index', compact('categories'));
    }

    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
