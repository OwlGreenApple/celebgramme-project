<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class SettingCounter extends Model {

	protected $fillable = ['created', 'setting_id', ];
	protected $table = 'setting_counters';
  public $timestamps = false;
  
}
