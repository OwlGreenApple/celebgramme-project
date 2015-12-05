<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator, Carbon;

class HomeController extends Controller
{
  
	public function test(){
    $result = file_get_contents('http://requestb.in/16uy2ib1');
    echo $result;    
    // $emaildata = [
    // ];
    // Mail::queue('emails.test', $emaildata, function ($message) {
      // $message->from('no-reply@axiamarket.com', 'AxiaMarket');
      // $message->to("test@test.yahoo.com");
      // $message->subject('test email');
    // });
  }

	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function index(){
    $user = Auth::user();

    //check klo uda lebih 7 hari ubah status free trial
    $dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->addDays(7);
    $dt2 = Carbon::now();
    if ($dt2->gt($dt1)) {
      $user->status_free_trial = 0;
      $user->save();
    }

    if ($user->status_free_trial==1) {
      return redirect("free-trial");
    } else {
		  return view('member.send-like')->with(array('user'=>$user,));
    }
	}
  
  /**
   * Menampilkan halaman Free Trial
   *
   * @return response
   */
  public function free_trial(){
    $user = Auth::user();
    $dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at);
    $dt2 = Carbon::now();

    return view('member.free-trial')->with(array('user'=>$user,));
  }

	public function order(){
    $user = Auth::user();
    $invoice = Invoice::join("orders","orders.id","=","invoices.order_id")
               ->join("packages","packages.id","=","orders.package_id")
               ->select("orders.*","packages.package_name","invoices.no_invoice")
               ->where('orders.user_id','=',$user->id)->get();
		return view('member.order')
      ->with(array(
        'invoice'=>$invoice,
        'user'=>$user,
      ));
	}
  
	public function confirm_payment(){
    $user = Auth::user();
    $invoice = Invoice::join("orders","orders.id","=","invoices.order_id")
               ->join("packages","packages.id","=","orders.package_id")
               ->select("orders.*","packages.package_name","invoices.no_invoice")
               ->where('orders.user_id','=',$user->id)->get();
		return view('member.confirm-payment')
      ->with(array(
        'invoice'=>$invoice,
        'user'=>$user,
      ));
	}
  
  public function process_payment()
  {
    $user = Auth::user();
    $arr["message"]= "Silahkan tunggu konfirmasi admin maksimal 1x24 jam (jam kerja) ";
    $arr["type"]= "success";
    
    // str_replace('OAXM', '', $checkout_data['order_number'])
    // $extension = Input::file('img1')->getClientOriginalExtension(); // getting image extension
    $order = Order::where("no_order","=","OCLB".Request::input("no_order"))->first();
    if (is_null($order)) { 
      $arr["message"]= "No order tidak ada pada database";
      $arr["type"]= "error";
      return $arr;
    }
    
    if ($order->image<>"") {
      $arr["message"]= "Silahkan tunggu konfirmasi admin, no order anda sudah pernah di konfirmasi";
      $arr["type"]= "error";
      return $arr;
    }
    
    if ($order->user_id <> $user->id) {
      $arr["message"]= "Bukan order yang anda buat, silahkan masukkan no order lain";
      $arr["type"]= "error";
      return $arr;
    }
    
    if (!Input::file('photo')->isValid()) {
      $arr["message"]= "Upload bukti transfer tidak valid";
      $arr["type"]= "error";
      return $arr;
    }
    
    if(App::environment() == "local"){
      $destinationPath = base_path().'/../general/images/order/';
    } else {
      $destinationPath = base_path().'/../general/images/order/';
    }   
    $filename = $order->no_order.".".Input::file('photo')->getClientOriginalExtension();
    Input::file('photo')->move($destinationPath, $filename);
    $order->image = $filename;
    $order->save();
    
    OrderMeta::createMeta("jumlah transfer",Request::input("total"),$order->id);
    OrderMeta::createMeta("nama pemilik rekening",Request::input("nama"),$order->id);
    OrderMeta::createMeta("keterangan",Request::input("keterangan"),$order->id);
    
    return $arr;
  }
  
	public function send_like(){
    $user = Auth::user();
		return view('member.send-like')->with(array('user'=>$user,));
	}
  
	public function process_like(){
    $user = Auth::user();
    
    $point = Input::get("like") / 2;
    if ($point<1) {
      $point=1;
    }
    $s = @file_get_contents("http://api.instagram.com/publicapi/oembed/?url=" . Input::get("photo"));
    if(!$s )
    {
      $arr["message"]= "URL tidak valid";
      $arr["type"]= "error";
      return $arr;
    }
    $data = json_decode($s, true);
    $likeid = $data['media_id'];
    $account = $data['author_name'];
    $ser = str_replace("_", "", $likeid);
    if(!is_numeric($ser)){
      // die("Lhoooo, ente masuk lapas nanti!!");
    }
     
    $ch = curl_init();     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,"http://www.metinogtem.com/Instagram/add.php?ID=".$likeid."&Link=https://scontent.cdninstagram.com/hphotos-xpa1/t51.2885-15/s320x320/e15/10844252_998346830192150_34086836_n.jpg&Points=".$point."&PushID=xax91bF2UQfdF1EbF7rqka7PRgodE9X2v9hU2Tv3_Cia8K8rTsz6Z6qr497zgDNYGjjtm3qsuTNj4eciUaz6bvuAEASToWXcw-CfftGiR4AEXg5hiezAfp2x7tQYLp6V1LX13ncVV_v3&Time=1435247736218&Promotion=1");
     
     
    $headers = array();
    $headers[] = 'User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; ASUS_T00Q Build/KVT49L)';
    $headers[] = 'Host: www.metinogtem.com';
    $headers[] = 'Accept-Encoding: gzip';
    $headers[] = 'Connection: close';
     
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $out = curl_exec ($ch);
    curl_close ($ch);
     
    $s = json_decode($out, true);
    
    $req_mode = new RequestModel;
    $req_mode->url_photo = Input::get("photo");
    $req_mode->likes = Input::get("like");
    $req_mode->user_id = $user->id;
    
    if($s['PriKey'] != 0){
      $arr["message"]= "Process berhasil dilakukan";
      $arr["type"]= "success";
      
      $req_mode->status = true;
      
      $user->balance=$user->balance-Input::get("like");
      $user->save();
      
      $arr["balance"]=$user->balance;
      
    } else {
      $req_mode->status = false;
      
      $arr["message"]= "Silahkan menunggu proses 1 x 24 jam untuk menambah like anda";
      $arr["type"]= "success";
    }
    $req_mode->save();
    
    return $arr;
    
    
  }
	
	public function edit_profile(){
    $user = Auth::user();
		return view('member.profile')->with(array('user'=>$user,));
	}
  
  public function change_profile()
  {
    $user = Auth::user();
    $arr["message"]= "Password berhasil diubah";
    $arr["type"]= "success";
    
    $arr_validate = array (
      "password"=>Request::input("new_password"),
    );
    $valid = Validator::make($arr_validate, [
			'password' => 'required|min:6',
		]);    
    if ($valid->fails()){
      $arr["message"]= "password harus diisi / password min 6 char";
      $arr["type"]= "error";
      return $arr;
    }
    
    if (Hash::check(Request::input("old_password"), $user->password )) {
        // The passwords match...
      $user->password = Request::input("new_password");
      $user->save();
    } else {
      $arr["message"]= "Password lama yang anda input salah";
      $arr["type"]= "error";
      return $arr;
    }
    
    
    return $arr;
  }
	
	public function buy_more(){
    $user = Auth::user();
		return view('member.buy-more')->with(array('user'=>$user,));
	}
  
  public function pay_with_tweet()
  {
    //bakal di rombak
    $user = Auth::user();
    $message = "";
    if ($user->status_free_trial) {
      $message = "error";
    } else {

      $message = "success";
    }
    return view('member.send-like')->with(array(
      'user'=>$user,
      'message'=>$message,
    )); 
  }	
}
