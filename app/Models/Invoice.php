<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Celebgramme\Models\Order;
use Celebgramme\Models\Package;
use Celebgramme\Models\PackageUser;
use Celebgramme\Models\User;

use Mail;

class Invoice extends Model {

	protected $table = 'invoices';
  
	protected function successPayment($cdata)
	{
    $invoice = new Invoice;
    $invoice->total = $cdata["order_total"];
    $invoice->order_id = $cdata["order_id"];
    $invoice->save();
    
    //update status order
    $order = Order::find($cdata["order_id"]);
    $order->order_status = "SUCCESS";
    $order->save();
    
    $packageUser = new PackageUser;
    $packageUser->package_id = $cdata["package_id"];
    $packageUser->user_id = $cdata["user_id"];
    $packageUser->save();
    
    $package = Package::find($cdata["package_id"]);
    $user = User::find($cdata["user_id"]);
    $user->balance = $package->daily_likes;
    $dt = Carbon::createFromFormat('Y-m-d H:i:s', $user->valid_until);
    if ($package->package_type=="day") {
      $user->valid_until = $dt->addDays(1)->toDateTimeString();
    }
    if ($package->package_type=="week") {
      $user->valid_until = $dt->addDays(7)->toDateTimeString();
    }
    if ($package->package_type=="month") {
      $user->valid_until = $dt->addDays(28)->toDateTimeString();
    }
    $user->pay_with_tweet = 2;
    $user->save();

    //send email success payment
    $emaildata = [
    ];
    Mail::queue('emails.success-payment', $emaildata, function ($message) use ($cdata) {
      $message->from('no-reply@celebgramme.com', 'Celebgramme');
      $message->to($cdata["email"]);
      $message->subject('Success Payment');
    });
  }
  
}
