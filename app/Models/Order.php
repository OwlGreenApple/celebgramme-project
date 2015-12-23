<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

use Celebgramme\Models\User;
use Celebgramme\Models\Package;

use Mail;

class Order extends Model {

	protected $table = 'orders';
  
	protected function createOrder($cdata,$flag)
	{
        $dt = Carbon::now();
        $coupon_id = 0;
        $coupon = Coupon::where("coupon_code","=",$cdata["coupon_code"])
                    ->where("valid_until",">=",$dt->toDateTimeString())->first();
        if (!is_null($coupon)) {
            $coupon_id = $coupon->id;
        }


        $order = new Order;
    		
    		$str = 'OCLB'.$dt->format('ymdHi');
        $order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
        $order->no_order = $order_number;
        $order->order_type = $cdata["order_type"];
        $order->order_status = $cdata["order_status"];
        $order->user_id = $cdata["user_id"];
        $order->total = $cdata["order_total"];
        // $order->package_id = $cdata["package_id"];
        $order->package_id = 0;
        $order->package_manage_id = $cdata["package_manage_id"];
        $order->coupon_id = $coupon_id;
        $order->save();

        $user = User::find($cdata["user_id"]);
        $package = Package::find($cdata["package_manage_id"]);
        $shortcode = str_replace('OCLB', '', $order_number);
        //send email order
        $emaildata = [
            'order' => $order,
            'user' => $user,
            'package' => $package,
            'no_order' => $shortcode,
        ];
        if ( $flag ) {
            $emaildata['status'] = "Belum lunas";
        } else {
            $emaildata['status'] = "Lunas";
        }
        Mail::queue('emails.order', $emaildata, function ($message) use ($user) {
          $message->from('no-reply@celebgramme.com', 'Celebgramme');
          $message->to($user->email);
          $message->subject('Order');
        });

        
        return $order;
  }
  
}
