<?php

namespace App\Actions\Expenses\ExpenseCategory;

use App\Models\ExpenseCategory;

/**
 * チームごとの品目カテゴリ取得アクション
 * 
 * このクラスは、特定のチームに関連する品目カテゴリの取得に関するアクションを管理します。
 */
class GetExpenseCategoriesByTeam
{
    /**
     * 指定されたチームIDに関連する品目カテゴリを取得する
     *
     * @param int $teamId 品目カテゴリを取得するチームのID
     * @return \Illuminate\Database\Eloquent\Collection 取得された品目カテゴリのコレクション
     */
    public function getByTeam(int $teamId)
    {
        return ExpenseCategory::where('team_id', $teamId)
            ->orderBy('order_column', 'asc')
            ->get();
    }
}
