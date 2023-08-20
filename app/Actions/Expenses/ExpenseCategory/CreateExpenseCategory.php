<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

/**
 * 品目カテゴリ作成アクション
 * 
 * このクラスは品目カテゴリの作成に関連するアクションを管理します。
 */
class CreateExpenseCategory
{
    /**
     * 品目カテゴリを作成する
     *
     * @param array $data 作成する品目カテゴリのデータ
     * @return \App\Models\ExpenseCategory 作成された品目カテゴリモデルインスタンス
     */
    public function create(array $data)
    {
        return ExpenseCategory::create($data);
    }
}
