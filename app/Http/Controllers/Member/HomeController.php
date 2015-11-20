<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail;

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
		return view('member.send-like')->with(array('user'=>$user,));
	}
  
	public function order(){
    $user = Auth::user();
		return view('member.order')->with(array('user'=>$user,));
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
     
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,"http://www.metinogtem.com/Instagram/add.php?ID=".$likeid."&Link=https://scontent.cdninstagram.com/hphotos-xpa1/t51.2885-15/s320x320/e15/10844252_998346830192150_34086836_n.jpg&Points=".$point."&PushID=APA91bF2UQfdF1EbF7rqka7PRgodE9X2v9hU2Tv3_Cia8K8rTsz6Z6qr497zgDNYGjjtm3qsuTNj4eciUaz6bvuAEASToWXcw-CfftGiR4AEXg5hiezAfp2x7tQYLp6V1LX13ncVV_v3&Time=1435247736218&Promotion=1");
     
     
    $headers = array();
    $headers[] = 'User-Agent: Dalvik/1.6.0 (Linux; U; Android 4.4.2; ASUS_T00Q Build/KVT49L)';
    $headers[] = 'Host: www.metinogtem.com';
    $headers[] = 'Accept-Encoding: gzip';
    $headers[] = 'Connection: close';
     
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $out = curl_exec ($ch);
    curl_close ($ch);
     
    $s = json_decode($out, true);
    if($s['PriKey'] != 0){
      $arr["message"]= "process berhasil dilakukan";
      $arr["type"]= "success";
      
      $req_mode = new RequestModel;
      $req_mode->url_photo = Input::get("photo");
      $req_mode->likes = Input::get("like");
      $req_mode->user_id = $user->id;
      $req_mode->save();
      
      $user->balance=$user->balance-Input::get("like");
      $user->save();
      
      $arr["balance"]=$user->balance;
      
    } else {
      $arr["message"]= "process gagal dilakukan silahkan coba lagi";
      $arr["type"]= "error";
    }
    
    return $arr;
    
    
  }
	
	public function edit_profile(){
    $user = Auth::user();
		return view('member.profile')->with(array('user'=>$user,));
	}
	
	public function buy_more(){
    $user = Auth::user();
		return view('member.buy-more')->with(array('user'=>$user,));
	}
  
	
}
