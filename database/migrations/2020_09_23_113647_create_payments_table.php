<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
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
            $table->integer('user_id');
            $table->bigInteger('receipt_no');
            $table->bigInteger('order_id');
            $table->integer('associate_id')->nullable();
            $table->double('commission')->nullable();

            $table->double('cgst')->nullable();
            $table->double('cgst_amount')->nullable();
            $table->double('sgst')->nullable();
            $table->double('sgst_amount')->nullable();
            $table->double('igst')->nullable();
            $table->double('igst_amount')->nullable();
            $table->longText('transaction_id');
            $table->longText('transaction_response')->nullable();
            $table->longText('transaction_response_status')->nullable();
            $table->longText('unique_key')->nullable();
            $table->integer('payment_status')->comment('1=>SUCCESS, 2=>FAILED');
            $table->integer('payment_mode')->default(1)->comment('1=>ONLINE, 2=>COD');

            $table->string('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->double('coupon_amount')->nullable();
            $table->double('reward_amount')->nullable();
            $table->double('pendrive_price')->nullable();
            $table->double('net_amount');

            $table->string('payment_url')->nullable();
            $table->timestamp('payment_url_expired_at')->nullable();
            $table->integer('payment_updated_by')->nullable();
            $table->integer('payment_updated_method')->comment('	1 => CC AVENUE, 2 => MANUAL, 3 => CRON')->nullable();
            $table->string('updated_ip_address')->nullable();

            $table->softDeletes();
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
}
