<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('name');
            $table->unsignedInteger('closing_date')->nullable();
            $table->unsignedInteger('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
