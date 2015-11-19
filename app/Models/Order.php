<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;

class Order extends Model {

	protected $table = 'orders';
  public $timestamps = false;
  
	protected function createOrder($cdata)
	{
    $order = new Order;
    $order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
    $order->no_order = $order_number;
    $order->order_type = "";
    $order->order_status = "";
    $order->user_id = "";
    $order->total = "";
    $order->save();
  }
  
}
