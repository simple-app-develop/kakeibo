<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function create()
    {
        return view('expenses.expense_categories.create');
    }

    public function store(Request $request, CreateExpenseCategory $action)
    {
        $teamId = auth()->user()->currentTeam->id;

        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $data['team_id'] = $teamId;

        $action->create($data);

        return redirect()->route('expense-category-index')->with('status', 'Category created successfully!');
    }

    public function index(GetExpenseCategoriesByTeam $action)
    {
        $categories = $action->getByTeam(auth()->user()->currentTeam->id);
        return view('expenses.expense_categories.index', compact('categories'));
    }
}
