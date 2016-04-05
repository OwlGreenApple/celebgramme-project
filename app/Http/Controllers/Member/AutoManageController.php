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
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Meta;
use Celebgramme\Models\Client;
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt;

class AutoManageController extends Controller
{
  
	public function __construct()
	{
			include('simple_html_dom.php');
	}
	
	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function index(){
    $user = Auth::user();
    $order = Order::where("order_status","=","pending")->where("user_id","=",$user->id)->where("image",'=','')->first();
		$status_server = Meta::where("meta_name","=","status_server")->first()->meta_value;
		
		$post = Post::where("type","=","home_page")->first();
		$content = $post->description;
		
    return view("member.auto-manage.index")->with(array(
      'user'=>$user,
      'order'=>$order,
      'status_server'=>$status_server,
      'content'=>$content,
      ));
	}


  public function process_edit_password(){  
    $user = Auth::user();
    $arr["message"]= "Ubah password berhasil dilakukan, sistem akan berjalan secara otomatis maksimum 1x24jam";
    $arr["type"]= "success";

    $data = array (
      // "insta_username"=>Request::input("edit_username"),
      "insta_password"=>Request::input("edit_password"),
      "user_id"=>$user->id,
      );

		$validator = Validator::make($data, [
			// 'insta_username' => 'required|max:255',
			'insta_password' => 'required',
		]);
		if ($validator->fails())
    {
			$arr["message"]= "Instagram password required";
			$arr["type"]= "error";
			return $arr;
		}
		//cek email, available username or not
		/*$validator = Validator::make($data, [
			'insta_username' => 'email',
		]);
		if (!$validator->fails())
    {
			$arr["message"]= "Instagram username tidak boleh email";
			$arr["type"]= "error";
			return $arr;
		}*/
			
		if($this->checking_cred_instagram(Request::input("hidden_username"),Request::input("edit_password"))) {
		} else {
			$arr["message"]= "Instagram Login tidak valid";
			$arr["type"]= "error";
			return $arr;
		}
			
			
    $setting_temp = Setting::find(Request::input('setting_id'));
    // $setting_temp->insta_username = Request::input('edit_username');
    $setting_temp->insta_password = Request::input('edit_password');
    $setting_temp->error_cred = false;
    $setting_temp->save();

    $setting_temp = Setting::post_info_admin($setting_temp->id);

    return $arr;
  }

  public function process_save_credential(){  
    $user = Auth::user();
    $arr["message"]= "Silahkan melakukan Account SETTING";
    $arr["type"]= "success";

    $data = array (
      "insta_username"=>Request::input("username"),
      "insta_password"=>Request::input("password"),
      "user_id"=>$user->id,
      );

		$validator = Validator::make($data, [
			'insta_username' => 'required|max:255',
			'insta_password' => 'required',
		]);
		if ($validator->fails())
    {
			$arr["message"]= "Instagram username or password required";
			$arr["type"]= "error";
			return $arr;
		}
		//cek not in email format
		$validator = Validator::make($data, [
			'insta_username' => 'email',
		]);
		if (!$validator->fails())
    {
			$arr["message"]= "Instagram username tidak boleh email";
			$arr["type"]= "error";
			return $arr;
		}
		
		//available username or not
		if ($user->test==0){
			if($this->checking_cred_instagram(Request::input("username"),Request::input("password"))) {
				
			} else {
				$arr["message"]= "Instagram Login tidak valid";
				$arr["type"]= "error";
				return $arr;
			}
		} else if ($user->test==1){
			$url = "http://websta.me/n/".Request::input("username");
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_REFERER, $url);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			$page = curl_exec($c);
			curl_close($c);
			
			$html = str_get_html($page);
			$profbox = $html->find('div[class="profbox"]');
			if (count($profbox)>0) {
			} else {
				$arr["message"]= "Instagram username not found";
				$arr["type"]= "error";
				return $arr;
			}
		} else if ($user->test==2){
			$found = false;
			$json_url = "https://api.instagram.com/v1/users/search?q=".Request::input("username")."&client_id=03eecaad3a204f51945da8ade3e22839";
			$json = @file_get_contents($json_url);
			if($json == TRUE) { 
				$links = json_decode($json);
				if (count($links->data)>0) {
					// $id = $links->data[0]->id;
					foreach($links->data as $link){
						if (strtoupper($link->username) == strtoupper(Request::input("username"))){
							$found = true;
						}
					}
				}
			}
			if (!$found) {
				$arr["message"]= "Instagram username not found";
				$arr["type"]= "error";
				return $arr;
			}
			
		}
		
		
    $setting = Setting::where("insta_username","=",Request::input("username"))->where("type","=","temp")->first();
    if (is_null($setting)) {
      $count_setting = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
												->select("settings.*")
												->where("link_users_settings.user_id","=",$user->id)
												->where("type","=","temp")
												->where("status","!=","deleted")
                        ->count();
      if ( $count_setting>=$user->max_account ) {
        $arr["message"]= "Account maksimal ".$user->max_account;
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
        if ($setting->status == "deleted") {
					$setting->status = "stopped";
				}
				$setting->insta_password = Request::input("password");
				$setting->error_cred = 0;
        $setting->save();
				
				$setting_temp = Setting::post_info_admin($setting->id);
      } else {
				if ( ($setting->status=="stopped") || ($setting->status=="started") ) {
					$arr["message"]= "Account anda sudah terdaftar";
					$arr["type"]= "error";
					return $arr;
				}
				
				if ($setting->status=="deleted") {
					$setting->status = 'stopped';
					$setting->user_id = $user->id;
					$setting->insta_password = Request::input("password");
					$setting->error_cred = 0;
					$setting->save();
					
					$setting_temp = Setting::post_info_admin($setting->id);
				}
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
              ->where("status","!=","deleted")
              ->get();

		$account_active = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
              ->where("status","=","started")
              ->count();
		if ($account_active==0) {
			$pembagi = 1;
		} else {
			$pembagi = $account_active;
		}
		$t = $user->active_auto_manage / $pembagi;
		$days = floor($t / (60*60*24));
		$hours = floor(($t / (60*60)) % 24);
		$minutes = floor(($t / (60)) % 60); if ($minutes<10) { $minutes = "0".$minutes; }
		$seconds = floor($t  % 60);
		if ($hours == 0 ) {
			$view_timeperaccount = $days." Days";
		} else {
			$view_timeperaccount = $days." Days ".$hours.":".$minutes;
		}
		
    return view("member.auto-manage.list-account")->with(array(
      'user'=>$user,
      'datas'=>$datas,
      'account_active'=>$account_active,
      'view_timeperaccount'=>$view_timeperaccount,
      ));
  }
  
  public function account_setting($id){  
    $user = Auth::user();

    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",$id)
              ->where("type","=","temp")
              ->first();

		/* Buat liat total waktu per akun*/
		$account_active = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
              ->where("status","=","started")
              ->count();
		if ($account_active==0) {
			$pembagi = 1;
		} else {
			$pembagi = $account_active;
		}
		$t = $user->active_auto_manage / $pembagi;
		$days = floor($t / (60*60*24));
		$hours = floor(($t / (60*60)) % 24);
		$minutes = floor(($t / (60)) % 60); if ($minutes<10) { $minutes = "0".$minutes; }
		$seconds = floor($t  % 60);
		if ($hours == 0 ) {
			$view_timeperaccount = $days." Days";
		} else {
			$view_timeperaccount = $days." Days ".$hours.":".$minutes;
		}

		/*Total waktu berlangganan*/
		$t = $user->active_auto_manage;
		$days = floor($t / (60*60*24));
		$hours = floor(($t / (60*60)) % 24);
		$minutes = floor(($t / (60)) % 60); if ($minutes<10) { $minutes = "0".$minutes; }
		$seconds = floor($t  % 60);
		if ($hours == 0 ) {
			$view_totaltime = $days." Days";
		} else {
			$view_totaltime = $days." Days ".$hours.":".$minutes;
		}


    if (is_null($link)) {
      return redirect('auto-manage')->with( 'error', 'Not authorize to access page');
    } 
    return view("member.auto-manage.account-setting")->with(array(
      'user'=>$user,
      'settings'=>$link,
      'view_timeperaccount'=>$view_timeperaccount,
      'view_totaltime'=>$view_totaltime,
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

    $setting_temp = Setting::find($data['id']);
		$following = intval (SettingMeta::getMeta($setting_temp->id,"following"));
		if (($following>7250 ) && ($data["activity"]=="follow") ) {
      $arr["message"]= "Tidak dapat melakukan activity following";
      $arr["type"]= "error";
      return $arr;
		}
		
		if ( ( ($data['status_comment']=="on") || ($data['status_like']=="on") ) || (($data["follow_source"]=="hashtags") && ($data['status_follow_unfollow']=="on") ) ) {
			$pieces = explode(";",$data["hashtags"]);
			if (count($pieces)<10) {
				$arr["message"]= "Hashtags minimal harus ada 10";
				$arr["type"]= "error";
				return $arr;
			}
			if (count($pieces)>100) {
				$arr["message"]= "Hashtags maximal 100";
				$arr["type"]= "error";
				return $arr;
			}
		}
		
		if ( ( ($data["follow_source"]=="followers of username") || ($data["follow_source"]=="following of username") ) && ($data['status_follow_unfollow']=="on") ) {
			$pieces = explode(";",$data["username"]);
			if (count($pieces)<10) {
				$arr["message"]= "Usernames minimal harus ada 10";
				$arr["type"]= "error";
				return $arr;
			}
		
			$pieces = explode(";",$data["username"]);
			if (count($pieces)>100) {
				$arr["message"]= "Usernames maximal 100";
				$arr["type"]= "error";
				return $arr;
			}
		}
		
		if ( ($setting_temp->status=="started") && ($data['status_follow_unfollow']=="off") && ($data['status_like']=="off") && ($data['status_comment']=="off") ) {
      $arr["message"]= "Silahkan pilih activity follow / like / comment";
      $arr["type"]= "error";
      return $arr;
		}
		
		if ($data['status_comment']=="on") {
			if ( (strpos($data['comments'], '<@owner>') !== false) && (strpos($data['comments'], '{') !== false) && (strpos($data['comments'], '}')!==false) ) {
			} else {
				$arr["message"]= "Comments memerlukan <@owner> dan spin comment";
				$arr["type"]= "error";
				return $arr;
			}
		}
		
		//hapus space di hashtags
		$data["hashtags"] = str_replace(" ","",$data["hashtags"]);
		$data["hashtags"] = str_replace("@","",$data["hashtags"]);
		$data["hashtags"] = str_replace("#","",$data["hashtags"]);
	
    if (isset($data['dont_comment_su'])) { $data['dont_comment_su'] = 1; } else { $data['dont_comment_su'] = 0; }
    if (isset($data['dont_follow_su'])) { $data['dont_follow_su'] = 1; } else { $data['dont_follow_su'] = 0; }
    if (isset($data['dont_follow_pu'])) { $data['dont_follow_pu'] = 1; } else { $data['dont_follow_pu'] = 0; }
    if (isset($data['unfollow_wdfm'])) { $data['unfollow_wdfm'] = 1; } else { $data['unfollow_wdfm'] = 0; }

    $setting_temp->update($data);

    
    $setting_temp = Setting::post_info_admin($setting_temp->id);


    $arr["message"]= "Setting berhasil diupdate";
    $arr["type"]= "success";
    return $arr;
  }

  public function call_action(){  
    $user = Auth::user();
		$status_server = Meta::where("meta_name","=","status_server")->first()->meta_value;
    $arr["message"]= "data berhasil di ubah";
		if ( (Request::input('action')=='start') && ($status_server=="maintenance") ) {
			$arr["message"]= "Settings akan dijalankan saat Status Server Normal/Delay";
		}
		
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
								->where("status","!=","deleted")
                ->get();
      foreach ($links as $link) {
        $setting_temp = Setting::find($link->setting_id);
				if ($setting_temp->error_cred==1) {
					$url = url('auto-manage');
					$arr["message"]= "Anda tidak dapat menjalankan program, silahkan update login credential account anda <a href='".$url."'>disini</a>";
					$arr["type"]= "error";
					return $arr;
				}
        if (Request::input('action')=='start') {
					if ( ($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off")&&($setting_temp->status_comment=="off") ) {
						$arr["message"]= "Anda tidak dapat menjalankan program, silahkan pilih aktifitas yang akan dilakukan (follow/like/comment) di account ".$setting_temp->insta_username;
						$arr["type"]= "error";
						return $arr;
					}
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
								->where("status","!=","deleted")
                ->first();
      if (!is_null($link)){
        $setting_temp = Setting::find($link->setting_id);
				if ($setting_temp->error_cred==1) {
					$url = url('auto-manage');
					$arr["message"]= "Anda tidak dapat menjalankan program, silahkan update login credential account anda <a href='".$url."'>disini</a>";
					$arr["type"]= "error";
					return $arr;
				}
        if (Request::input('action')=='start') {
					if ( ($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off")&&($setting_temp->status_comment=="off") ) {
						$arr["message"]= "Anda tidak dapat menjalankan program, silahkan pilih aktifitas yang akan dilakukan (follow/like/comment) ";
						$arr["type"]= "error";
						return $arr;
					}
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


    $arr["type"]= "success";
    return $arr;
  }

	public function delete_setting(){  
		$user = Auth::user();
    $arr["message"]= "Account berhasil dihapus";
    $arr["type"]= "success";
		
    $account = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
              ->where("link_users_settings.setting_id","=",Request::input("id"))
              ->first();
		if (is_null($account)) {
			$arr["message"]= "Account tidak berhasil dihapus";
			$arr["type"]= "error";
			return $arr;
			
		} else {
			$setting = Setting::find($account->id);
			$setting->status = "deleted";
			$setting->save();
			
			$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (Delete IG Account)");
		}
		
		return $arr;
	}

	public function agree_terms(){  
		$user = Auth::user();
		$user->agree_term_condition=1;
		$user->save();
		
		return "success";
	}

	public function checking_cred_instagram($username,$password){  
		//old
		// $ports[] = "10255";
		// $ports[] = "10254";
		//new
		$ports[] = "10207";
		$ports[] = "10208";
		$ports[] = "10209";
		$port = $ports[array_rand($ports)];
		$cred = "sugiarto:sugihproxy250";
		$proxy = "45.79.212.85";//good proxy

		$url = "https://www.instagram.com/accounts/login/?force_classic_login";
		if(App::environment() == "local"){		
			$cookiefile = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$username.'-cookiess.txt';
		}
		$c = curl_init();
		curl_setopt($c, CURLOPT_PROXY, $proxy);
    curl_setopt($c, CURLOPT_PROXYPORT, $port);
		curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
    curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
    $page = curl_exec($c);
    curl_close($c);
    preg_match_all('/<input type="hidden" name="csrfmiddlewaretoken" value="([A-z0-9]{32})"\/>/', $page, $token);
		
    $c = curl_init();
		curl_setopt($c, CURLOPT_PROXY, $proxy);
    curl_setopt($c, CURLOPT_PROXYPORT, $port);
		curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
    curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, "csrfmiddlewaretoken=".$token[1][0]."&username=".$username."&password=".$password);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
    $page = curl_exec($c);
    curl_close($c);

		unlink($cookiefile);
		$html = str_get_html($page);
		$check_error = $html->find('div[id="alerts"]');
		if (count($check_error)>0) {
			// echo "error login";
			return false;
		} else {
			//login success
			$check_csrf = $html->find('input[name="csrfmiddlewaretoken"]');
			if (count($check_csrf)>0) {
				// echo "error csrf";
				return false;
			} else {
				// echo "masuk";
				return true;
			}
		}
		

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_HTTPHEADER, array(
        'Accept-Language: en-US,en;q=0.8',
        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36',
        'Accept: */*',
        'X-Requested-With: XMLHttpRequest',
        'Connection: keep-alive'
        ));	
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
    $page = curl_exec($c);
    curl_close($c);
		unlink($cookiefile);
		preg_match_all('/<input type="hidden" name="csrfmiddlewaretoken" value="([A-z0-9]{32})"\/>/', $page, $token);
		if (count($token[1])==0) { //login valid
			return true;
		} else { //login invalid
			return false;
		}
	}
}
