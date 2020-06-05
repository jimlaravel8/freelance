<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->boolean('active')->default(true);
            $table->string('name', 64);
            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->char('currency', 3)->nullable();
            $table->tinyInteger('currency_fraction_digits')->unsigned()->nullable();
            $table->integer('initial_bonus_points')->unsigned()->nullable();
            $table->integer('points_expiration_months')->unsigned()->nullable();
            $table->integer('points_per_currency')->unsigned()->nullable();
            $table->decimal('point_value', 8, 4)->unsigned()->nullable();
            $table->bigInteger('min_points_per_purchase')->unsigned()->nullable();
            $table->bigInteger('max_points_per_purchase')->unsigned()->nullable();
            $table->bigInteger('min_points_per_redemption')->unsigned()->nullable();
            $table->bigInteger('max_points_per_redemption')->unsigned()->nullable();
            $table->string('email', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('mobile', 32)->nullable();
            $table->string('website', 250)->nullable();
            $table->string('fax', 32)->nullable();
            $table->string('street1', 250)->nullable();
            $table->string('street2', 250)->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('state', 64)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->tinyInteger('zoom')->nullable();
            $table->decimal('lat', 17, 14)->nullable();
            $table->decimal('lng', 18, 15)->nullable();
            $table->boolean('is_online_business')->nullable();
            $table->string('vat_number', 250)->nullable();
            $table->string('id_number', 250)->nullable();
            $table->string('bank', 250)->nullable();
            $table->string('bank_id', 250)->nullable();
            $table->string('ecode_swift', 250)->nullable();
            $table->string('iban', 250)->nullable();
            $table->json('social')->nullable();
            $table->json('settings')->nullable();
            $table->json('attributes')->nullable();
            $table->json('meta')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->timestamps();
        });

        // Many-to-many relation
        Schema::create('business_user_favorites', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('business_id')->unsigned()->index();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Many-to-many relation
        Schema::create('business_user_trusted', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('business_id')->unsigned()->index();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_user_trusted');
        Schema::dropIfExists('business_user_favorites');
        Schema::dropIfExists('businesses');
    }
}
