<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Actions\Expenses\ExpenseCategory\ReorderExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryReorderRequest;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use App\Http\Requests\Expenses\ExpenseCategoryUpdateRequest;
use App\Models\ExpenseCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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

    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        return view('expenses.expense_categories.edit', compact('category'));
    }

    public function update(ExpenseCategoryUpdateRequest $request, $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            $category->update([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);

            return redirect()->route('expense-category-index')->with('success', 'Category updated successfully!');
        } catch (QueryException $e) {
            // 一意性制約違反のエラーコードは "23000" です。
            if ($e->getCode() === "23000") {
                return redirect()->back()
                    ->withErrors(['name' => trans('messages.category_name_taken')])
                    ->withInput();
            }
            return redirect()->back()->withErrors(['error' => trans('messages.db_error')])->withInput();
        }
    }

    private function getCurrentTeamId()
    {
        return auth()->user()->currentTeam->id;
    }
}
