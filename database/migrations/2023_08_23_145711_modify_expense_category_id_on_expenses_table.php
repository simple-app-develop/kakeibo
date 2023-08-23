<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyExpenseCategoryIdOnExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['expense_category_id']);

            // expense_category_idをNULL許容に変更
            $table->unsignedBigInteger('expense_category_id')->nullable()->change();

            // 新しい外部キー制約を設定
            $table->foreign('expense_category_id')
                ->references('id')
                ->on('expense_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['expense_category_id']);

            // expense_category_idのNULL許容を取り消し（必要に応じてデフォルト値やその他の設定も調整してください）
            $table->unsignedBigInteger('expense_category_id')->nullable(false)->change();
        });
    }
}
