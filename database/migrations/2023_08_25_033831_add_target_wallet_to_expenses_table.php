<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetWalletToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // 移動先のお財布IDを表すカラムを追加
            $table->unsignedBigInteger('target_wallet_id')->nullable()->after('wallet_id');

            // type カラムに 'transfer' を追加
            $table->enum('type', ['income', 'expense', 'transfer'])->default('expense')->change();

            // 外部キー制約の追加
            $table->foreign('target_wallet_id')->references('id')->on('wallets')->onDelete('set null');
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
            // type カラムから 'transfer' を削除
            $table->enum('type', ['income', 'expense'])->default('expense')->change();

            // target_wallet_id カラムとその外部キー制約を削除
            $table->dropForeign(['target_wallet_id']);
            $table->dropColumn('target_wallet_id');
        });
    }
}
