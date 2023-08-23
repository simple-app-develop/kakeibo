<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('type', ['income', 'expense'])->default('expense')->after('user_id');
            $table->unsignedBigInteger('wallet_id')->nullable()->after('type');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
            $table->dropColumn('type');
        });
    }
};
