<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorespondersettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_responder_settings', function (Blueprint $table) {
            $table->increments('id');
						$table->integer('setting_id')->nullable();
						$table->text('message')->nullable();
						$table->integer('num_of_day')->nullable();
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
        Schema::drop('auto_responder_settings');
    }
}
