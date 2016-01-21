<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

class SettingMeta extends Model {

	protected $table = 'setting_metas';
  public $timestamps = false;
  
	protected function createMeta($name,$value,$id)
	{
		$setting_meta = SettingMeta::
											where("setting_id","=",$id)
											->where("meta_name","=",$name)
											->first();
		if (is_null($setting_meta)) {
			$setting_meta = new SettingMeta;
		}
    $setting_meta->meta_name = $name;
    $setting_meta->meta_value = $value;
    $setting_meta->setting_id = $id;
    $setting_meta->save();
  }
  
	protected function getMeta($id,$meta_name)
	{
		$setting_meta = SettingMeta::where('setting_id','=',$id)->where("meta_name","=",$meta_name)->first();
		if (!is_null($setting_meta)) {
			return $setting_meta->meta_value;
		} else {
			return "0";
		}
	}
}
