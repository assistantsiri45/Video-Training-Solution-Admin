<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('coupon_type')->comment("1=> PUBLIC, 2=> PRIVATE")->default(1);
            $table->double('amount');
            $table->tinyInteger('amount_type')->comment("1=> FLAT, 2=> PERCENTAGE")->default(1);
            $table->integer('coupon_per_user');
            $table->integer('total_coupon_limit');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->tinyInteger('status')->comment("1=> DRAFT, 2=> PUBLISH, 3=>UNPUBLISH")->default(1);
            $table->double('min_purchase_amount')->nullable();
            $table->double('max_purchase_amount')->nullable();
            $table->double('max_discount_amount');
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
        Schema::dropIfExists('coupons');
    }
}
