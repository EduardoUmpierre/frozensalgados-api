<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersFieldsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function($table) {
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->tinyInteger('installments')->nullable();
            $table->tinyInteger('payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function($table) {
            $table->dropColumn('payment_date');
            $table->dropColumn('delivery_date');
            $table->dropColumn('installments');
            $table->dropColumn('payment_method');
        });
    }
}
