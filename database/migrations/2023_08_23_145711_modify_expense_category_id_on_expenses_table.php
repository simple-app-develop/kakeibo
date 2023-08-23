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
            // 外部キー制約を再設定
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
            $table->dropForeign(['expense_category_id']); // 外部キー制約を削除
            $table->foreign('expense_category_id')        // 制約を元に戻す場合、こちらの行をコメントアウトまたは削除
                ->references('id')
                ->on('expense_categories');
        });
    }
}
