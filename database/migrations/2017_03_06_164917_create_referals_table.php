<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
		1. ada user free trial 3 hari, ada user old
		2. didaftar klo klik referal maka user old & user free trial dapat tambahan 10 hari
		3. 
		*/
		Schema::create('referrals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('downline_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('status')->nullable();
			$table->boolean('is_buy')->nullable();
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
		Schema::drop('actions');
	}
}
