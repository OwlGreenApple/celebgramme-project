<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;


class Account extends Model {

	protected $table = 'accounts';
	protected $connection = 'mysql_celebpost';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ ];
}
