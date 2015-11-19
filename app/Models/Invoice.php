<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Celebgramme\Models\Order;

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
