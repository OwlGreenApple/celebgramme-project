<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{ 
		//id, name,value(text),type(comment_template),user_id,created_at,updated_at
		Schema::create('templates', function (Blueprint $table) {
			$table->increments('id');
			$table->string('type')->nullable();
			$table->string('name')->nullable();
			$table->text('value')->nullable();
			$table->integer('user_id')->nullable();
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
		Schema::drop('templates');
	}
}
