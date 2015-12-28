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
use Celebgramme\Models\Post;
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt;

class AutoManageController extends Controller
{
  
	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function index(){
    $user = Auth::user();
    $order = Order::where("order_status","=","pending")->where("user_id","=",$user->id)->where("image",'=','')->first();
    return view("member.auto-manage.index")->with(array(
      'user'=>$user,
      'order'=>$order,
      ));
	}


  public function process_edit_password(){  
    $user = Auth::user();
    $arr["message"]= "Ubah password berhasil dilakukan, sistem akan berjalan secara otomatis maksimum 1x24jam";
    $arr["type"]= "success";

    $setting_temp = Setting::find(Request::input('setting_id'));
    $setting_temp->insta_password = Request::input('edit_password');
    $setting_temp->error_cred = false;
    $setting_temp->save();

    $setting_temp = Setting::post_info_admin($setting_temp->id);

    return $arr;
  }

  public function process_save_credential(){  
    $user = Auth::user();
    $arr["message"]= "Sistem berhasil diupdate, sistem akan berjalan secara otomatis maksimum 1x24jam";
    $arr["type"]= "success";

    $data = array (
      "insta_username"=>Request::input("username"),
      "insta_password"=>Request::input("password"),
      "user_id"=>$user->id,
      );

    $setting = Setting::where("insta_username","=",Request::input("username"))->where("type","=","temp")->first();
    if (is_null($setting)) {
      $count_setting = LinkUserSetting::where("user_id","=",$user->id)
                          ->count();
      if ( $count_setting>=3 ) {
        $arr["message"]= "Account maksimal 3";
        $arr["type"]= "error";
        return $arr;
      }
      $setting = Setting::createSetting($data);
    } else {
      $linkUserSetting = LinkUserSetting::where("user_id","=",$user->id)
                          ->where("setting_id","=",$setting->id)
                          ->first();
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

              // dd($user->toArray());
    if (is_null($link)) {
      return redirect('auto-manage')->with( 'error', 'Not authorize to access page');
    } 
    return view("member.auto-manage.account-setting")->with(array(
      'user'=>$user,
      'settings'=>$link,
      ));
  }

  public function process_save_setting(){  
    $user = Auth::user();
    $data = Request::input("data");
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",$data['id'])
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      $arr["message"]= "Not authorize";
      $arr["type"]= "error";
      return $arr;
    }


    if (isset($data['dont_comment_su'])) { $data['dont_comment_su'] = 1; } else { $data['dont_comment_su'] = 0; }
    if (isset($data['dont_follow_su'])) { $data['dont_follow_su'] = 1; } else { $data['dont_follow_su'] = 0; }
    if (isset($data['dont_follow_pu'])) { $data['dont_follow_pu'] = 1; } else { $data['dont_follow_pu'] = 0; }
    if (isset($data['unfollow_wdfm'])) { $data['unfollow_wdfm'] = 1; } else { $data['unfollow_wdfm'] = 0; }

    $setting_temp = Setting::find($data['id']);
    $setting_temp->update($data);

    
    $setting_temp = Setting::post_info_admin($setting_temp->id);


    $arr["message"]= "Setting berhasil diupdate";
    $arr["type"]= "success";
    return $arr;
  }

  public function call_action(){  
    $user = Auth::user();
    $arr["action"]= Request::input('action');
    $arr["id"]= Request::input('id');

    if ( ($user->active_auto_manage==0) && ((Request::input('action')=='start')) ) {
      $arr["message"]= "Anda tidak dapat menjalankan program, silahkan upgrade waktu anda";
      $arr["type"]= "error";
      return $arr;
    }

    $dt = Carbon::now();
    if (Request::input('id')=='all') {
      $links = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
                ->where("link_users_settings.user_id","=",$user->id)
                ->where("type","=","temp")
                ->get();
      foreach ($links as $link) {
        $setting_temp = Setting::find($link->setting_id);
        if (Request::input('action')=='start') {
          $setting_temp->status = "started";
          $setting_temp->start_time = $dt->toDateTimeString();
          $setting_temp->running_time = $dt->toDateTimeString();
        }

        if (Request::input('action')=='stop') {
          $setting_temp->status = "stopped";
        }
        $setting_temp->save();

        $setting_temp = Setting::post_info_admin($setting_temp->id);
      }
    } else {
      $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
                ->where("link_users_settings.user_id","=",$user->id)
                ->where("settings.id","=",Request::input('id'))
                ->where("type","=","temp")
                ->first();
      if (!is_null($link)){
        $setting_temp = Setting::find($link->setting_id);
        if (Request::input('action')=='start') {
          $setting_temp->status = "started";
          $setting_temp->start_time = $dt->toDateTimeString();
          $setting_temp->running_time = $dt->toDateTimeString();
        }

        if (Request::input('action')=='stop') {
          $setting_temp->status = "stopped";
        }
        $setting_temp->save();

        $setting_temp = Setting::post_info_admin($setting_temp->id);
      }
    }


    $arr["message"]= "data berhasil di ubah";
    $arr["type"]= "success";
    return $arr;
  }

}
