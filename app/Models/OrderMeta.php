<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

class OrderMeta extends Model {

	protected $table = 'order_metas';
  public $timestamps = false;
  
	protected function createMeta($value,$name,$id)
	{
    $order_meta = new OrderMeta;
    $order_meta->meta_value = $value;
    $order_meta->meta_name = $name;
    $order_meta->order_id = $id;
    $order_meta->save();
  }
  
}
