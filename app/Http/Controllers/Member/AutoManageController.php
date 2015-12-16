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

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt;

class AutoManageController extends Controller
{
  
	public function test(){
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
    return view("member.auto-manage")->with(array(
      'user'=>$user,
      ));
	}


  public function process_save_credential(){
  }
  
  
}
