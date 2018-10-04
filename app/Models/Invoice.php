<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Celebgramme\Models\Order;
use Celebgramme\Models\Package;
use Celebgramme\Models\PackageUser;
use Celebgramme\Models\User;

use Carbon\Carbon;

use Mail;

class Invoice extends Model {

	protected $table = 'invoices';
  
	protected function successPayment($cdata)
	{
    $invoice = new Invoice;

    $dt = Carbon::now();
    $str = 'ICLB'.$dt->format('ymdHi');
    $invoice_number = GeneralHelper::autoGenerateID($invoice, 'no_invoice', $str, 3, '0');

    $invoice->no_invoice = $invoice_number;
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
    $user->status_free_trial = 0;
    $user->save();

    //send email success payment
    $shortcode = str_replace('ICLB', '', $invoice_number);
    $emaildata = [
        'no_invoice' => $shortcode,
        'package' => $package,
    ];
    if ($order->order_type=="transfer_bank") {
        $emaildata["order_type"] = "Transfer Bank";
    }
    if ($order->order_type=="VERITRANS") {
        $emaildata["order_type"] = "Veritrans";
    }
    Mail::queue('emails.success-payment', $emaildata, function ($message) use ($cdata) {
      $message->from('no-reply@activfans.com', 'activfans');
      $message->to($cdata["email"]);
      $message->subject('Success Payment');
    });
  }
  
}
