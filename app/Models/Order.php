<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

class Order extends Model {

	protected $table = 'orders';
  public $timestamps = false;
  
	protected function createOrder($cdata)
	{
    $order = new Order;
		$dt = Carbon::now();
		$str = 'OCLB'.$dt->format('ymdHi');
    $order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
    $order->no_order = $order_number;
    $order->order_type = $cdata["order_type"];
    $order->order_status = $cdata["order_status"];
    $order->user_id = $cdata["user_id"];
    $order->total = $cdata["order_total"];
    $order->save();
    
    return $order;
  }
  
}
