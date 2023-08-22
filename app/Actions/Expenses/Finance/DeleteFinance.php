<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;

/**
 * 家計簿データ削除アクション
 * 
 * このクラスは家計簿データの削除に関連するアクションを管理します。
 */
class DeleteFinance
{
    /**
     * 家計簿データデータを削除する
     *
     * 指定された家計簿データのモデルインスタンスを削除します。
     *
     * @param Expense $finance 削除する家計簿データのモデルインスタンス
     * @return void
     */
    public function delete(Expense $finance)
    {
        $finance->delete();
    }
}
