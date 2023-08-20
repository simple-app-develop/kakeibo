<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use App\Models\ExpenseCategory;
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

        return redirect()->route('expense-category-index')->with('status', 'Category created successfully!');
    }

    public function index()
    {
        $categories = $this->getExpenseCategoriesByTeamAction->getByTeam($this->getCurrentTeamId());
        return view('expenses.expense_categories.index', compact('categories'));
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        if (!is_array($order) || empty($order)) {
            return response()->json(['message' => 'Invalid order data provided'], 400);
        }

        // リクエストから送られてきた順序に従ってorder_columnを更新
        foreach ($order as $index => $id) {
            $category = ExpenseCategory::find($id);
            if ($category) {
                $category->order_column = $index;
                $category->save();
            }
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
