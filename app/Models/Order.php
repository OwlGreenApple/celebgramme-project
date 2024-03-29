<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Helpers\GeneralHelper;
use Carbon\Carbon;

use Celebgramme\Models\User;
use Celebgramme\Models\Package;
use Celebgramme\Models\PackageAffiliate;
use Celebgramme\Models\OrderMeta;
use Illuminate\Database\Eloquent\SoftDeletes;

use Mail;

class Order extends Model {

	protected $table = 'orders';
	// use SoftDeletes;
  
	protected function createOrder($cdata,$flag)
	{
        $dt = Carbon::now();
        $coupon_id = 0;$order_discount = 0;
        $coupon = Coupon::where("coupon_code","=",$cdata["coupon_code"])
                    ->where("valid_until",">=",$dt->toDateTimeString())->first();
        if (!is_null($coupon)) {
            $coupon_id = $coupon->id;
						
					if ($coupon->coupon_percent == 0 ) {
						$order_discount = $coupon->coupon_value;
					} else if ($coupon->coupon_value == 0 ) {
						if ($cdata["type"] == "daily-activity" ) {
							$val = floor ( $coupon->coupon_percent / 100 * $cdata["order_total"] );
							$order_discount = $val;
						}
						else if ($cdata["type"] == "max-account" ) {
						}
					}
						
        }
				
				

				//unique code 
				$unique_code = mt_rand(1, 1000);
        $order = new Order;
    		$str = 'OCLB'.$dt->format('ymdHi');
        $order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
        $order->no_order = $order_number;
        $order->order_type = $cdata["order_type"];
        $order->order_status = $cdata["order_status"];
        $order->user_id = $cdata["user_id"];
        $order->total = $cdata["order_total"] + $unique_code;
        $order->discount = $order_discount;
        $order->package_id = 0;
        $order->package_manage_id = $cdata["package_manage_id"];
        $order->coupon_id = $coupon_id;
				
				$order->type = $cdata["type"];
				$order->is_remind_email = 0;
				
				if ($cdata["type"] == "daily-activity" ) {
					$order->added_account = 0;
				}
				else if ($cdata["type"] == "max-account" ) {
					$order->added_account = $cdata["maximum_account"];
				}
        $order->save();
				
				OrderMeta::createMeta("logs","create order by ".$cdata["logs"],$order->id);

        $user = User::find($cdata["user_id"]);

        if(env('APP_PROJECT')=='Celebgramme'){
          $package = Package::find($cdata["package_manage_id"]);
        } else {
          $paket = explode('999', $cdata["package_manage_id"]);
          $package = PackageAffiliate::find($paket[1]);
        }
        
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

        if(env('APP_PROJECT')=='Celebgramme') {
          Mail::queue('emails.order', $emaildata, function ($message) use ($user,$shortcode) {
            $message->from('no-reply@activfans.com', 'activfans');
            $message->to($user->email);
            $message->subject('[activfans] Order Nomor '.$shortcode);
          });

  				
  				//send email to admin
  				$type_message="[activfans] Order Package";
  				$type_message .= "Fullname: ".$user->fullname;
  				$emaildata = [
  					"user" => $user,
  					"status" => "order",
  				];
  				Mail::queue('emails.info-order-admin', $emaildata, function ($message) use ($type_message) {
  					$message->from('no-reply@activfans.com', 'activfans');
  					$message->to(array(
  						"michaelsugih@gmail.com",
  						"celebgramme.dev@gmail.com",
  					));
  					$message->subject($type_message);
  				});
        } else {
          Mail::queue('emails.order', $emaildata, function ($message) use ($user,$shortcode) {
            $message->from('no-reply@activfans.com', 'activfans');
            $message->to($user->email);
            $message->subject('[Amelia] Order Nomor '.$shortcode);
          });

          
          //send email to admin
          $type_message="[Amelia] Order Package";
          $type_message .= "Fullname: ".$user->fullname;
          $emaildata = [
            "user" => $user,
            "status" => "order",
          ];
          Mail::queue('emails.info-order-admin', $emaildata, function ($message) use ($type_message) {
            $message->from('no-reply@activfans.com', 'activfans');
            $message->to(array(
              "support@amelia.id",
              "activfans@gmail.com",
              "celebgramme.dev@gmail.com",
            ));
            $message->subject($type_message);
          });  
        }
				
        
        return $order;
  }
  
}
