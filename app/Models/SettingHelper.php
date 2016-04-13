<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Client;

use Celebgramme\Models\SettingMeta;

use Mail;

class SettingHelper extends Model {

	protected $table = 'setting_helpers';
  public $timestamps = false;
	
}
