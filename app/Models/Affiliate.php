<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;


class Affiliate extends Model {

	protected $table = 'affiliates';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ ];
}
