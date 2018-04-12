<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model {
	protected $table = 'surveys';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
													"no_undian", 
													"email", 
													"fullname",
													"city",
													"is_bisnis",
													"is_selebgram",
													"popular_olshop",
													"selebgram",
												];

	
}
