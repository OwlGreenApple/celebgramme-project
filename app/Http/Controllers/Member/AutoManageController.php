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
use Celebgramme\Models\Setting;
use Celebgramme\Models\LinkUserSetting;
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
    return view("member.auto-manage.index")->with(array(
      'user'=>$user,
      ));
	}


  public function process_save_credential(){  
    $user = Auth::user();
    $arr["message"]= "Process add account berhasil";
    $arr["type"]= "success";

    $data = array (
      "insta_username"=>Request::input("username"),
      "insta_password"=>Request::input("password"),
      "user_id"=>$user->id,
      );

    $setting = Setting::where("insta_username","=",Request::input("username"))->where("type","=","real")->first();
    if (is_null($setting)) {
      $setting = Setting::createSetting($data);
    } else {
      $linkUserSetting = LinkUserSetting::where("user_id","=",$user->id)->first();
      if (is_null($linkUserSetting)) {
        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$user->id;
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();

        $setting->last_user = $user->id;
        $setting->save();

      } else {
        $arr["message"]= "Account anda sudah terdaftar";
        $arr["type"]= "error";
        return $arr;
      }
    }

    return $arr;
  }
  
  public function load_account(){  
    $user = Auth::user();

    $datas = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
              ->get();

    return view("member.auto-manage.list-account")->with(array(
      'user'=>$user,
      'datas'=>$datas,
      ));
  }
  
  public function account_setting($id){  
    $user = Auth::user();

    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",$id)
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('auto-manage')->with( 'error', 'Not authorize to access page');
    } 
    return view("member.auto-manage.account-setting")->with(array(
      'user'=>$user,
      ));
  }

}
