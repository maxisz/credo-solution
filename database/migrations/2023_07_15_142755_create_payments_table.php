<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer("amount")->nullable();
            $table->string("tinypesa_result")->nullable();
            $table->string("tinypesa_account_id");
            $table->string("tinypesa_request_id");
            $table->string("tinypesa_payment_amount")->nullable();
            $table->string("tinypesa_result_code")->nullable();
            $table->string("tinypesa_result_desc")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
