<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('business_id')->unsigned()->index();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('customer_number', 32)->nullable();
            $table->bigInteger('staff_id')->unsigned()->nullable()->index();
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
            $table->string('staff_first_name', 64)->nullable();
            $table->string('staff_last_name', 64)->nullable();
            $table->string('staff_email', 128)->nullable();
            $table->char('currency', 3)->nullable();
            $table->tinyInteger('currency_fraction_digits')->unsigned()->nullable();
            $table->integer('points');
            $table->integer('points_used')->unsigned()->default(0);
            $table->integer('points_per_currency')->unsigned()->nullable();
            $table->decimal('point_value', 8, 4)->unsigned()->nullable();
            $table->bigInteger('purchase_amount')->unsigned()->nullable();
            $table->string('event', 250)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('updated_by')->nullable()->unsigned();
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
        Schema::dropIfExists('history');
    }
}
