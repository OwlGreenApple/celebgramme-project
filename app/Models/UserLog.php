<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model {

	protected $connection = 'mysql_axs';
	protected $table = 'user_logs';
	public $timestamps = false;
}
