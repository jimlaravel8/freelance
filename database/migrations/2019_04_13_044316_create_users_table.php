<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->tinyInteger('role')->unsigned()->default(3);
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->dateTime('premium_expires_at')->nullable();
            $table->string('customer_number', 32)->nullable();
            $table->string('remote_customer_id', 200)->nullable();
            $table->string('previous_remote_customer_id', 200)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('verification_code', 64)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->char('token', 32)->nullable();
            $table->string('language', 5)->nullable();
            $table->string('locale', 24)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('timezone', 32)->nullable();
            $table->integer('logins')->default(0)->unsigned();
            $table->dateTime('last_login')->nullable();
            $table->ipAddress('last_ip_address')->nullable();
            $table->tinyInteger('emails_sent')->unsigned()->default(0);
            $table->rememberToken();

            $table->string('salutation', 32)->nullable();
            $table->string('first_name', 64)->nullable();
            $table->string('last_name', 64)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('job_title', 64)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('lead_source', 250)->nullable();
            $table->string('lead_source_module', 128)->nullable();
            $table->string('lead_status', 128)->nullable();
            $table->string('lead_type', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('mobile', 32)->nullable();
            $table->string('website', 250)->nullable();
            $table->string('fax', 32)->nullable();
            $table->string('street1', 250)->nullable();
            $table->string('street2', 250)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('state', 64)->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->char('country_code', 2)->nullable();

            // Location
            $table->tinyInteger('zoom')->nullable();
            $table->decimal('lat', 17, 14)->nullable();
            $table->decimal('lng', 18, 15)->nullable();

            $table->string('company', 96)->nullable();
            $table->string('shipping_street1', 250)->nullable();
            $table->string('shipping_street2', 250)->nullable();
            $table->string('shipping_city', 64)->nullable();
            $table->string('shipping_state', 64)->nullable();
            $table->string('shipping_postal_code', 32)->nullable();
            $table->char('shipping_country_code', 2)->nullable();

            // Shipping location
            $table->tinyInteger('shipping_zoom')->nullable();
            $table->decimal('shipping_lat', 17, 14)->nullable();
            $table->decimal('shipping_lng', 18, 15)->nullable();

            $table->string('vat_number', 250)->nullable();
            $table->string('id_number', 250)->nullable();
            $table->string('bank', 250)->nullable();
            $table->string('bank_id', 250)->nullable();
            $table->string('ecode_swift', 250)->nullable();
            $table->string('iban', 250)->nullable();
            $table->mediumText('notes')->nullable();
            $table->json('settings')->nullable();
            $table->json('meta')->nullable();

            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();
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
        Schema::dropIfExists('users');
    }
}
