<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class FailedJob extends Model {

	protected $table = 'failed_jobs';
  public $timestamps = false;
  
}
