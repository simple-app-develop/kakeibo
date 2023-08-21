<?php

namespace App\Http\Controllers\Expenses;

use App\Actions\Expenses\ExpenseCategory\CreateExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\DeleteExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\EditExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\GetExpenseCategoriesByTeam;
use App\Actions\Expenses\ExpenseCategory\ReorderExpenseCategory;
use App\Actions\Expenses\ExpenseCategory\UpdateExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseCategoryReorderRequest;
use App\Http\Requests\Expenses\ExpenseCategoryStoreRequest;
use App\Http\Requests\Expenses\ExpenseCategoryUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * 品目カテゴリコントローラ
 * 
 * このクラスは品目カテゴリに関連するアクションを管理します。
 */
class ExpenseCategoryController extends Controller
{
    // 品目カテゴリに関連するアクションのプロパティ定義
    protected $createExpenseCategoryAction;
    protected $getExpenseCategoriesByTeamAction;
    protected $reorderExpenseCategoryAction;
    protected $editExpenseCategoryAction;
    protected $updateExpenseCategoryAction;
    protected $deleteExpenseCategoryAction;

    public function __construct(
        CreateExpenseCategory $createExpenseCategoryAction,
        GetExpenseCategoriesByTeam $getExpenseCategoriesByTeamAction,
        ReorderExpenseCategory $reorderExpenseCategoryAction,
        EditExpenseCategory $editExpenseCategoryAction,
        UpdateExpenseCategory $updateExpenseCategoryAction,
        DeleteExpenseCategory $deleteExpenseCategoryAction,
    ) {
        $this->createExpenseCategoryAction = $createExpenseCategoryAction;
        $this->getExpenseCategoriesByTeamAction = $getExpenseCategoriesByTeamAction;
        $this->reorderExpenseCategoryAction = $reorderExpenseCategoryAction;
        $this->editExpenseCategoryAction = $editExpenseCategoryAction;
        $this->updateExpenseCategoryAction = $updateExpenseCategoryAction;
        $this->deleteExpenseCategoryAction = $deleteExpenseCategoryAction;
    }

    /**
     * 品目カテゴリの作成画面表示
     */
    public function create()
    {
        try {
            $view = $this->createExpenseCategoryAction->create();
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
        // 品目カテゴリ作成ビューを返す
        return $view;
    }

    /**
     * カテゴリの保存
     *
     * @param ExpenseCategoryStoreRequest $request リクエストデータ
     */
    public function store(ExpenseCategoryStoreRequest $request)
    {
        $data = $request->all();
        $data['team_id'] = $this->getCurrentTeamId();
        try {
            $this->createExpenseCategoryAction->store($data);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        return redirect()->route('expense-category-index')->with('success', 'Category created successfully!');
    }

    /**
     * カテゴリの一覧表示
     */
    public function index()
    {
        // 現在のチームに関連するカテゴリの一覧を取得してビューを返す
        $categories = $this->getExpenseCategoriesByTeamAction->getByTeam($this->getCurrentTeamId());
        return view('expenses.expense_categories.index', compact('categories'));
    }

    /**
     * カテゴリの並び替え
     *
     * @param ExpenseCategoryReorderRequest $request リクエストデータ
     */
    public function reorder(ExpenseCategoryReorderRequest $request)
    {
        try {
            $order = $request->input('order');

            $result = $this->reorderExpenseCategoryAction->reorder($order);

            if ($result['status']) {
                return response()->json(['message' => $result['message']]);
            } else {
                return response()->json(['message' => $result['message']], 400);
            }
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * カテゴリの削除
     *
     * @param int $id カテゴリID
     */
    public function destroy($id)
    {
        try {
            $result = $this->deleteExpenseCategoryAction->delete($id);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
        if ($result['status']) {
            return redirect()->route('expense-category-index')->with('success', $result['message']);
        } else {
            return redirect()->route('expense-category-index')->withErrors(['error' => $result['message']]);
        }
    }

    /**
     * カテゴリの編集画面表示
     *
     * @param int $id カテゴリID
     */
    public function edit($id)
    {
        try {
            $category = $this->editExpenseCategoryAction->get($id, $this->getCurrentTeamId());
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
        return view('expenses.expense_categories.edit', compact('category'));
    }

    /**
     * カテゴリの更新
     *
     * @param ExpenseCategoryUpdateRequest $request リクエストデータ
     * @param int $id カテゴリID
     */
    public function update(ExpenseCategoryUpdateRequest $request, $id)
    {
        try {
            $result = $this->updateExpenseCategoryAction->update($id, $request->all(), $this->getCurrentTeamId());
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }

        return redirect()->route('expense-category-index')->with('success', $result['message']);
    }

    /**
     * 現在のチームIDを取得
     *
     * @return int 現在のチームID
     */

    private function getCurrentTeamId()
    {
        // 認証済みのユーザーから現在のチームIDを取得して返す
        return auth()->user()->currentTeam->id;
    }
}
