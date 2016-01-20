<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\Package;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\Setting;
use Celebgramme\Models\LinkUserSetting;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, Redirect;

class HomeController extends Controller
{
  
	public function test(){
		$user = Auth::user();
		$decrypted = Crypt::decrypt("asd");
		return $decrypted;
		$linkusersettings = LinkUserSetting::all();
		foreach ($linkusersettings as $linkusersetting) {
			$setting = Setting::find($linkusersetting->setting_id);
			$setting->user_id = $linkusersetting->user_id;
			$setting->save();
		}
		return "b";
		
		
    return Redirect::to("http://celebgramme.com/email-konfirmasi/");
    return strval(false);
    $url = "http://play.vid-id.me/aff_c?offer_id=16&aff_id=3104";
    return view('member.pay-with-tweet')->with(array(
      'user'=>$user,
      'url'=>$url,
    ));
    // $url2 = "http://facebook.com";
    // $url1 = url("/");
    // return view('member.temp')->with(array(
    //   'url1'=>$url1,
    //   'url2'=>$url2,
    // )); 

    // $secret_data = [
    //   'day' => 7,
    // ];

    $decryptedcode = Crypt::decrypt($cryptedcode);
    $data = json_decode($decryptedcode);
    dd($data);
    return Crypt::encrypt(json_encode($secret_data));
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

    $dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at);
    $dt2 = Carbon::now();
    /*remark disable daily like
    if ( ($user->status_free_trial==1) && ( $user->used_free_trial <= $dt1->diffInDays($dt2) ) ) { 
      return redirect("free-trial");
    } else {
      return view('member.send-like')->with(array('user'=>$user,));
    }*/
		return view('member.auto-manage.index')->with(array('user'=>$user,));
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

    if ($dt1->diffInDays($dt2) == 0 ) {
      // $url = "http://www.paywithapost.de/pay?id=936b8163-2248-4fee-8766-430b3654757c";
      $url = "http://adf.ly/1T9X3x";
    }
    if ($dt1->diffInDays($dt2) == 1 ) {
      // $url = "http://www.paywithapost.de/pay?id=4d1cd293-87a1-4141-ba95-b0bd319e2539";
      $url = "http://adf.ly/1T9XDP";
    }
    if ($dt1->diffInDays($dt2) == 2 ) {
      // $url = "http://www.paywithapost.de/pay?id=b14b3a16-eb04-4957-801a-7da3cbe0a06e";
      $url = "http://adf.ly/1T9XHT";
    }
    if ($dt1->diffInDays($dt2) == 3 ) {
      // $url = "http://www.paywithapost.de/pay?id=f350cf20-3759-4ec5-90ff-d55af2b2fccc";
      $url = "http://adf.ly/1T9XLT";
    }
    if ($dt1->diffInDays($dt2) == 4 ) {
      // $url = "http://www.paywithapost.de/pay?id=1fd0b365-fc7b-454d-87f6-8c44c806a11e";
      $url = "http://adf.ly/1T9XPz";
    }
    if ($dt1->diffInDays($dt2) == 5 ) {
      // $url = "http://www.paywithapost.de/pay?id=572b4892-108e-490b-8505-44ede32f3044";
      $url = "http://adf.ly/1T9XTw";
    }
    if ($dt1->diffInDays($dt2) == 6 ) {
      $url = "http://adf.ly/1T9XWc";
    }

    return view('member.free-trial')->with(array(
      'user'=>$user,
      'url'=>$url,
    ));
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
		return view('member.confirm-payment')
      ->with(array(
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
      $destinationPath = base_path().'/../htdocs/general/images/confirm-payment/';
    } else {
      $destinationPath = base_path().'/../public_html/general/images/confirm-payment/';
    }   
    $filename = $order->no_order.".".Input::file('photo')->getClientOriginalExtension();
    Input::file('photo')->move($destinationPath, $filename);
    $order->image = $filename;
    $order->save();
    
    OrderMeta::createMeta("jumlah transfer",Request::input("total"),$order->id);
    OrderMeta::createMeta("nama pemilik rekening",Request::input("nama"),$order->id);
    OrderMeta::createMeta("nama bank",Request::input("nama_bank"),$order->id);
    OrderMeta::createMeta("no rekening",Request::input("no_rekening"),$order->id);
    OrderMeta::createMeta("keterangan",Request::input("keterangan"),$order->id);

    //send email success payment
    $emaildata = [
      'no_order'=>Request::input("no_order"),
      'jumlah_transfer'=>Request::input("total"),
      'nama'=>Request::input("nama"),
      'no_rekening'=>Request::input("no_rekening"),
      'nama_bank'=>Request::input("nama_bank"),
      'keterangan'=>Request::input("keterangan"),
    ];
    Mail::queue('emails.confirm-order', $emaildata, function ($message) use ($user) {
      $message->from('no-reply@celebgramme.com', 'Celebgramme');
      $message->to($user->email);
      $message->bcc(array(
        "celebgram@gmail.com",
        "michaelsugih@gmail.com",
        "it2.axiapro@gmail.com",
        "celebgramme.adm@gmail.com",
        ));
      $message->subject('[Celebgramme] Order Confirmation');
    });

    
    return $arr;
  }
  
	public function send_like(){
    $user = Auth::user();
		return view('member.send-like')->with(array('user'=>$user,));
	}
  
	public function process_like(){
    $user = Auth::user();

    $dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $user->valid_until);
    $dt2 = Carbon::now();
    if ($dt2->gt($dt1)) {
      $arr["message"]= "Masa berlaku paket anda telah habis";
      $arr["type"]= "error";
    }

    if ($user->balance<Input::get("like")){
      $arr["message"]= "Balance anda tidak mencukupi";
      $arr["type"]= "error";
    }
    
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
	
	public function buy_more($id = null){
    $user = Auth::user();
		$packages = Package::where("package_group","=","auto-manage")->where("affiliate","=",0)->get();
		return view('member.buy-more')->with(
			array(
				'user'=>$user,
				'id'=>$id,
				'packages'=>$packages,
			));
	}
  
  /*
  * kembalian dari pay with tweet dengan emkripan data, data akan mengupdate used free trial agar free trial page hari tersebut tidak muncuk lagi
  */
  public function confirm_paywithtweet($cryptedcode)
  {
    $user = Auth::user();

    // $secret_data = [
    //   'day' => 1,
    // ];

    // return Crypt::encrypt(json_encode($secret_data));
    //day 1 
    //eyJpdiI6IjM3d3dGXC9kUllnNCt0NnFZU1pyZWt3PT0iLCJ2YWx1ZSI6IlRSOEwzS0pZNExMYXhuR1I0WTVXNVd1ZUpSTHYzSWFLN2ZjMW82Z212QlU9IiwibWFjIjoiYTI5MWQxZTMzMTZkMWEyZGVlOTE4NDlhZDUxNzk0NjI1NmJlMWI5ZmEwOWJmYzEwZDUzODU3NzMyZDNmYTQ4ZCJ9
    //http://celebgramme.com/celebgramme/confirm-paywithtweet/
    //day2
    //eyJpdiI6IlY1SmgrdUdyV0NBRXJVM01oRnRtd1E9PSIsInZhbHVlIjoiSzJ1am1kanJaSStRVWdkZHFLODNRRGpKM3IrRGx3dHN1Z3VudG52K0pXST0iLCJtYWMiOiIzZGEzNDUwZjE0ODU3M2NjY2IyNjczNjRjMWIzOTUzM2IzMzliNDFjM2E0NTM2NGZhNDZjOGFhYWI0ZDAzMWJiIn0=
    //day3
    //eyJpdiI6Im03RENrTzBPTjd4NzJwZEZjTGJTYWc9PSIsInZhbHVlIjoiVmRLeHh6OVFFdlBvWFdiUXhONUdcL3dXanpoWTJUck9BME9BUll5c2pENVU9IiwibWFjIjoiMzM2YTFjMzU0MmIxMTRmMDNiODI1NTY1ZTdjYjhmMjY4NGMxNGQ3ZTI5NDg0MGJmZWUwYTZiZDAwNmNhZWNkYiJ9
    //day4
    //eyJpdiI6IkJTbSsrUzJvMGZjK0JhZFB2dG00UVE9PSIsInZhbHVlIjoiZUttMWF6YkliaFNFU0w3RDQwM3ROa0loZnBqQTY4bzBLdldYOVE5YkszYz0iLCJtYWMiOiI2OWM2ZDdiMGY3MjQxYTBiMzQxMTlkYzZjODMyZDA5OTY3MDgxODQ4NmEwMWVkZWVhZTNhZmJlMTNkYWI1YTAxIn0=
    //day5
    //eyJpdiI6IlA3ZEcxSXltYXl4MUZnQjkxaSs2SEE9PSIsInZhbHVlIjoiWjN3am04SEpCcmNCMERcL1ZjbXZPNk8yRDJkREdHNjJQQnZ1Z1BhWGxMZzA9IiwibWFjIjoiNWRlZWFjNTI5ZGY5MWIwYmI2MDU5ZDI1MmIwODkxMjJlODFiZDcxOThiYjUwNDMzZjQxMjBhNGJhYWQ5MjdkMyJ9
    //day6
    //eyJpdiI6IkY1MENtamwya3U3eXp5TUpnYjBxWWc9PSIsInZhbHVlIjoiZ1dSN1EwMjJZUEVEU2JORmI3a2E3cGtvZjdrMjE5NFpUK29XWGJLYjdBVT0iLCJtYWMiOiJiZTFkODg5OWIwOTNlNjJmOWQyODhkYWEwY2ZiMzdmNDUwMzZkYzQ0YWZlYjAyYzExMWU0YzE4ZmIzYTdkMDMxIn0=
    //day7
    //eyJpdiI6IkNwM1lWdDFyZng4SVUzR2hsOFczNUE9PSIsInZhbHVlIjoiM0RcL0tYVUN5RW5YbU5WUTc0cHR2U2JnRStNblZxT2VCUjVLSHlPZkhaK2M9IiwibWFjIjoiZDU3MTQwZDEyODg4NzFkMWFiZmRhMTgzODJjMzgzZTQ2N2U5ZmQzMzhmZjMxODE1MDMyN2UwMDcxY2IzYjBmZiJ9
    $decryptedcode = Crypt::decrypt($cryptedcode);
    $data = json_decode($decryptedcode);
    if (($data->day==1) && ($user->used_free_trial<$data->day) ) { $user->balance= 200; }
    if (($data->day==2) && ($user->used_free_trial<$data->day) ) { $user->balance= 250; }
    if (($data->day==3) && ($user->used_free_trial<$data->day) ) { $user->balance= 300; }
    if (($data->day==4) && ($user->used_free_trial<$data->day) ) { $user->balance= 350; }
    if (($data->day==5) && ($user->used_free_trial<$data->day) ) { $user->balance= 400; }
    if (($data->day==6) && ($user->used_free_trial<$data->day) ) { $user->balance= 450; }
    if (($data->day==7) && ($user->used_free_trial<$data->day) ) { $user->balance= 500; }
    $user->used_free_trial = $data->day;
    $user->save();

    if ( ($data->day==7) ) {
      // $url = "http://watch.vid-id.me/aff_c?offer_id=22&aff_id=3104&source=celebgramme-free";
      $url2 = "http://adf.ly/1T9TMF";
    }
    if ( ($data->day==5) ) {
      // $url = "http://play.vid-id.me/aff_c?offer_id=18&aff_id=3104";
      // $url2 = "http://adf.ly/1TDWL9";
      $url2 = "http://adf.ly/1T9WsE";
    }
    if ( ($data->day==6) ) {
      // $url = "http://play.vid-id.me/aff_c?offer_id=16&aff_id=3104";
      $url2 = "http://adf.ly/1T9WsE";
    }

    // return view('member.pay-with-tweet')->with(array(
    //   'user'=>$user,
    //   'url'=>$url,
    // ));
    $url1 = url("/");
    if ( ($data->day==5) || ($data->day==6) || ($data->day==7) ) {
      return redirect("/")->with("cpa",$url2);
    } else {
      return redirect("/");
    }

  }	
}
