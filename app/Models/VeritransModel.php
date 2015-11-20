<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

class VeritransModel extends Model {

	protected $table = 'veritrans';
  public $timestamps = false;
	
	protected function setValue($unique_id, $json_value)
	{
		$vm = VeritransModel::where('unique_id', '=', $unique_id)->first();
		if (is_null($vm)){
			$vm = new VeritransModel;
			$vm->unique_id = $unique_id;
			if ($json_value == null){
				$vm->delete();
				return true;
			}
		}
		$vm->json_value = $json_value;
		$vm->save();
		return true;
	}
	protected function getValue($unique_id)
	{
		$vm = VeritransModel::where('unique_id', '=', $unique_id)->first();
		if (!is_null($vm)){
      return json_decode($vm->json_value);
    }
    else{
      return '';
    }
	}
}
