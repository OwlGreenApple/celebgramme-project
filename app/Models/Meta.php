<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

class Meta extends Model {

	protected $table = 'metas';
  
	protected function createMeta($name,$value)
	{
		$meta = Meta::where("meta_name","=",$name)
									->first();
		if (is_null($meta)) {
			$meta = new Meta;
		}
    $meta->meta_name = $name;
    $meta->meta_value = $value;
    $meta->save();
  }
  
	protected function getMeta($meta_name)
	{
		$meta = Meta::where("meta_name","=",$meta_name)->first();
		if (!is_null($meta)) {
			return $meta->meta_value;
		} else {
			return "0";
		}
	}
}
