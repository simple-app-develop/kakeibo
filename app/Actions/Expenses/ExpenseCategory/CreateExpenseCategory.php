<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;
use App\Services\Expenses\ExpensePermissionService;

/**
 * 品目カテゴリ作成アクション
 * 
 * このクラスは品目カテゴリの作成に関連するアクションを管理します。
 */
class CreateExpenseCategory
{
    /**
     * 品目カテゴリサービス
     *
     * @var ExpensePermissionService
     */
    protected $expensePermissionService;

    /**
     * CreateExpenseCategory コンストラクタ
     *
     * 依存性を注入してプロパティを初期化します。
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function __construct(ExpensePermissionService $expensePermissionService)
    {
        $this->expensePermissionService = $expensePermissionService;
    }

    /**
     * 品目カテゴリ作成ビューを返す
     *
     * ユーザーが品目カテゴリを作成する権限があるかを確認した後、
     * 品目カテゴリ作成ビューを返します。
     *
     * @throws \Exception アクセス権限がない場合に例外をスローします。
     * @return \Illuminate\View\View 品目カテゴリ作成ビュー
     */
    public function create()
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('category')) {
            throw new \Exception('You are not authorized to create categories on this team.');
        }

        // 品目カテゴリ作成ビューを返す
        return view('expenses.expense_categories.create');
    }

    /**
     * 品目カテゴリを作成する
     *
     * @param array $data 作成する品目カテゴリのデータ
     * @throws \Exception アクセス権限がない場合に例外をスローします。
     * @return \App\Models\ExpenseCategory 作成された品目カテゴリモデルインスタンス
     */
    public function store(array $data)
    {
        // 権限を確認する
        if (!$this->expensePermissionService->checkPermission('category')) {
            throw new \Exception('You are not authorized to create categories on this team.');
        }

        return ExpenseCategory::create($data);
    }
}
