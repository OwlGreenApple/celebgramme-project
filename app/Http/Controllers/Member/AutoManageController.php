<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as req;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Models\UserLog;
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Meta;
use Celebgramme\Models\Client;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Proxies;
use Celebgramme\Models\ProxyLogin;
use Celebgramme\Models\Category;
use Celebgramme\Models\SettingLog;
use Celebgramme\Models\TimeLog;
use Celebgramme\Models\Account;
use Celebgramme\Models\Message;
use Celebgramme\Models\UserSetting;

use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\ViewProxyUses;

use Celebgramme\Helpers\GlobalHelper;

use \InstagramAPI\Instagram;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, DB, Config, Exception;

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
	public function index(req $request){
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


  public function process_edit_password(req $request){  
    $user = Auth::user();
    $arr["message"]= "Ubah password berhasil dilakukan, tekan tombol start lalu sistem akan berjalan secara otomatis maksimum 1x24jam";
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
			
		/*$arr_proxy = null;
		if($this->checking_cred_instagram(Request::input("hidden_username"),Request::input("edit_password"),$arr_proxy,Request::input('setting_id') )) {
		} else {
			$arr["message"]= "Instagram Login tidak valid";
			$arr["type"]= "error";
			return $arr;
		} Old ganti password*/
		
		//cari proxy dulu
		//use own proxy if have
		$setting_temp = Setting::find(Request::input('setting_id'));
		$setting_helper = SettingHelper::where("setting_id","=",Request::input('setting_id'))->first();
		if (!is_null($setting_helper)) {
			if ($setting_helper->proxy_id== 0) {
				// $arr_proxy = $this->get_proxy_id(Request::input("hidden_username")); 
				$arr_proxy = GlobalHelper::clearProxy(serialize($setting_temp), "new");
				$setting_helper->proxy_id = $arr_proxy["proxy_id"];
				$setting_helper->save();
			}
			$full_proxy =  Proxies::find($setting_helper->proxy_id);
			if (!is_null($full_proxy)) {
				$arr_proxy["port"] = $full_proxy->port;
				$arr_proxy["cred"] = $full_proxy->cred;
				$arr_proxy["proxy"] = $full_proxy->proxy;
				$arr_proxy["auth"] = $full_proxy->auth;
			}
			else {
				// codingan tambal an buat cover proxy id yang sudah dihapus
				// dicariin proxy
				$arr_proxy = $this->get_proxy_id(Request::input("hidden_username")); //
				$update_setting_helper = SettingHelper::where("setting_id",Request::input('setting_id'))->first();
				if(!is_null($update_setting_helper)){
					$update_setting_helper->proxy_id = $arr_proxy["proxy_id"];
					$update_setting_helper->save();
				}
			}
		}
			/* New Checking password*/
			$is_error = 0 ;
			$error_message = "";
			try {
				$i = new Instagram(false,false,[
					"storage"       => "mysql",
					"dbhost"       => Config::get('automation.DB_HOST'),
					"dbname"   => Config::get('automation.DB_DATABASE'),
					"dbusername"   => Config::get('automation.DB_USERNAME'),
					"dbpassword"   => Config::get('automation.DB_PASSWORD'),
				]);
				
				//proxy things
				if (session()->has('proxy')) {
					$arr_proxy_login = session('proxy');
					if ( intval(session('attempt_count')) == 1 ) {
						//flag proxy supaya ga dipake lagi hari itu
						$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
														->where("port",$arr_proxy_login["port"])
														->where("cred",$arr_proxy_login["cred"])
														->first();
						if(!is_null($proxy_login)){
							$proxy_login->is_error = 1;
							$proxy_login->save();
						}
						
						//random dengan proxy yang lain
						$arr_proxy_login = $this->random_proxy();
					}
					else if ( intval(session('attempt_count')) >= 3 ) {
						//flag proxy supaya ga dipake lagi hari itu
						$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
														->where("port",$arr_proxy_login["port"])
														->where("cred",$arr_proxy_login["cred"])
														->first();
						if(!is_null($proxy_login)){
							$proxy_login->is_error = 1;
							$proxy_login->save();
						}
						
						$arr["message"] = "Mohon maaf, Anda telah melebihi batas login attempt 2x untuk menghindari akun anda di flag oleh Instagram Silahkan coba kembali setelah 3 x 24 jam";
						$arr["type"] = "error";
						return $arr;
					}
					
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => intval(session('attempt_count')) + 1 ,
					]);
				}
				else {
					//new code, supaya klo sentry block bisa aktif lagi 
					$user_setting = UserSetting::where("username",$setting_temp->insta_username)->delete();
					
					$arr_proxy_login = $this->random_proxy();
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => 0,
					]);
				}
				if($arr_proxy_login['cred']==""){
					$i->setProxy("http://".$arr_proxy_login['proxy'].":".$arr_proxy_login['port']);
				}
				else {
					$i->setProxy("http://".$arr_proxy_login['cred']."@".$arr_proxy_login['proxy'].":".$arr_proxy_login['port']);
				}
				
				$i->login(Request::input("hidden_username"), Request::input("edit_password"), 1800);
			} 
			catch (\InstagramAPI\Exception\IncorrectPasswordException $e) {
				//klo error password
				$is_error = 1 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\AccountDisabledException $e) {
				//klo error password
				$is_error = 1 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\CheckpointRequiredException $e) {
				//klo error email / phone verification 
				$is_error = 2 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\ChallengeRequiredException $e) {
				$is_error = 2 ;
				$error_message = $e->getMessage();
			}
			catch (Exception $e) {
				$error_message = $e->getMessage();
				
				// if ($error_message == "InstagramAPI\Response\LoginResponse: The password you entered is incorrect. Please try again.") {
				if (strpos($error_message, 'InstagramAPI\Response\LoginResponse:') !== false) {
					$is_error = 1 ;
					$error_message = $e->getMessage();
				} 
				if ( ($error_message == "InstagramAPI\Response\LoginResponse: Challenge required.") || ( substr($error_message, 0, 18) == "challenge_required") || ($error_message == "InstagramAPI\Response\TimelineFeedResponse: Challenge required.") || ($error_message == "InstagramAPI\Response\LoginResponse: Sorry, there was a problem with your request.") ){
					$is_error = 2 ;
					$error_message = $e->getMessage();
				}
				if ( (strpos($e->getMessage(), 'Network: CURL error') !== false) || (strpos($e->getMessage(), 'No response from server') !== false) ) {
					//proxy ga bisa, flag proxy supaya ga dipake lagi hari itu
					$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
													->where("port",$arr_proxy_login["port"])
													->where("cred",$arr_proxy_login["cred"])
													->first();
					if(!is_null($proxy_login)){
						$proxy_login->is_error = 2;
						$proxy_login->save();
					}
					
					$arr_proxy_login = $this->random_proxy();
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => 0,
					]);
					
					$arr["message"] = "Network error, silahkan coba lagi";
					$arr["type"] = "error";
					return $arr;
				}
			}

			
		if ($is_error == 1) {
			$arr["message"]= "Instagram Login is not valid";
			// $arr["error_message"]= $error_message;
			$arr["type"]= "error";
			return $arr;
		}
		else if ($is_error == 2) {
			$arr["message"]= "Error Confirmation";
			// $arr["error_message"]= $error_message;
			$arr["type"]= "error2";
      $dt = Carbon::now();
			$user_log = new UserLog;
			$user_log->email = $user->email." ".Request::input("hidden_username");
			$user_log->admin = "";
			$user_log->description = "Error Proxy : ".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"].":".$arr_proxy_login["cred"];
			$user_log->created = $dt->toDateTimeString();
			$user_log->save();
			return $arr;
		}
		else if ($is_error == 0) {
			SettingMeta::createMeta("error_message_cred","",Request::input('setting_id'));
		}
			
			
      $dt = Carbon::now();
			$user_log = new UserLog;
			$user_log->email = $user->email." ".Request::input("hidden_username");
			$user_log->admin = "";
			$user_log->description = "Success add Proxy : ".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"].":".$arr_proxy_login["cred"]." Proxy activity : ".$arr_proxy["proxy"].":".$arr_proxy["port"].":".$arr_proxy["cred"];
			$user_log->created = $dt->toDateTimeString();
			$user_log->save();
    
			session([
				'proxy' => collect($arr_proxy_login),
				'attempt_count' => 0 ,
			]);
			
    // $setting_temp->insta_username = Request::input('edit_username');
    $setting_temp->insta_password = Request::input('edit_password');
    $setting_temp->error_cred = false;
    $setting_temp->save();

    // $setting_temp = Setting::post_info_admin($setting_temp->id);
		
		//for automation purpose
		$setting_helper = SettingHelper::where("setting_id","=",Request::input('setting_id'))->first();
		if (!is_null($setting_helper)) {
			$setting_helper->cookies = "";
			$setting_helper->is_refresh = 1;
			$setting_helper->save();
		}

    return $arr;
  }

  public function process_save_credential(req $request){  
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
		
		//tidak boleh mengandung "@", " "
		if (strpos(Request::input("username"), '@') !== false) {
			$arr["message"]= "Instagram username tidak boleh mengandung @";
			$arr["type"]= "error";
			return $arr;
		}
		if (strpos(Request::input("username"), ' ') !== false) {
			$arr["message"]= "Instagram username tidak boleh mengandung space";
			$arr["type"]= "error";
			return $arr;
		}
		
		//check max_account
		$count_setting = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
											->select("settings.*")
											->where("link_users_settings.user_id","=",$user->id)
											->where("type","=","temp")
											->where("is_active","=", 1)
											->where("status","!=","deleted")
											->count();
		if ( $count_setting>=$user->max_account ) {
			$arr["message"]= "Account maksimal ".$user->max_account;
			$arr["type"]= "error";
			return $arr;
		}
			
		//Instagram Login Valid or not
		// if ($user->test==0){
			$setting = Setting::join("setting_helpers","setting_helpers.setting_id","=","settings.id")
									->select("settings.id","setting_helpers.proxy_id")
									->where("insta_username","=",Request::input("username"))
									->where("type","=","temp")
									// ->where("proxy_id","<>",0)
									->first();
			if (!is_null($setting)) {
				if ($setting->proxy_id== 0) {
					$arr_proxy = $this->get_proxy_id(Request::input("username")); //
					$update_setting_helper = SettingHelper::where("setting_id",$setting->id)->first();
					if(!is_null($update_setting_helper)){
						$update_setting_helper->proxy_id = $arr_proxy["proxy_id"];
						$update_setting_helper->save();
					}
				}

				if ($setting->proxy_id <> 0) {
					$full_proxy =  Proxies::find($setting->proxy_id);
					if (!is_null($full_proxy)) {
						$arr_proxy["port"] = $full_proxy->port;
						$arr_proxy["cred"] = $full_proxy->cred;
						$arr_proxy["proxy"] = $full_proxy->proxy;
						$arr_proxy["auth"] = $full_proxy->auth;
					}
					else {
						// codingan tambal an buat cover proxy id yang sudah dihapus
						// dicariin proxy
						$arr_proxy = $this->get_proxy_id(Request::input("username")); //
						$update_setting_helper = SettingHelper::where("setting_id",$setting->id)->first();
						if(!is_null($update_setting_helper)){
							$update_setting_helper->proxy_id = $arr_proxy["proxy_id"];
							$update_setting_helper->save();
						}
						
					}
				} 
				
			} 
			else {
				$arr_proxy = $this->get_proxy_id(Request::input("username")); 
			}
				
			$data["arr_proxy"] = $arr_proxy; //
			//check login valid / invalid
			/*
			if($this->checking_cred_instagram(Request::input("username"),Request::input("password"),$arr_proxy)) {
				//check klo uda ada gaa usa create lagi 
				$setting = Setting::where("insta_username","=",Request::input("username"))
										->where("type","=","temp")
										->first();
				if (is_null($setting)) {
					$setting_id_temp = Setting::createSetting($data);
				}else {
					$setting_id_temp = $setting->id;
				}
			} else {
				//check klo uda ada gaa usa create lagi 
				$setting = Setting::where("insta_username","=",Request::input("username"))
										->where("type","=","temp")
										->first();
				if (is_null($setting)) {
					$setting_id_temp = Setting::createSetting($data);
				}
				$arr["message"]= "Instagram Login tidak valid";
				$arr["type"]= "error";
				return $arr;
			} Old Checking password*/
		 
			//check klo uda ada gaa usa create lagi 
			$setting = Setting::where("insta_username","=",Request::input("username"))
									->where("type","=","temp")
									->first();
			if (is_null($setting)) {
				$setting_id_temp = Setting::createSetting($data);
			}
			else {
				$setting_id_temp = $setting->id;
			}
			
			/* New Checking password*/
			$is_error = 0 ;
			$error_message = "";
			// $response_error = "";
			// $trace_string = "";
			try {
				$i = new Instagram(false,false,[
					"storage"       => "mysql",
					"dbhost"       => Config::get('automation.DB_HOST'),
					"dbname"   => Config::get('automation.DB_DATABASE'),
					"dbusername"   => Config::get('automation.DB_USERNAME'),
					"dbpassword"   => Config::get('automation.DB_PASSWORD'),
				]);
				
				//proxy things
				if (session()->has('proxy')) {
					$arr_proxy_login = session('proxy');
					if ( intval(session('attempt_count')) == 1 ) {
						//flag proxy supaya ga dipake lagi hari itu
						$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
														->where("port",$arr_proxy_login["port"])
														->where("cred",$arr_proxy_login["cred"])
														->first();
						if(!is_null($proxy_login)){
							$proxy_login->is_error = 1;
							$proxy_login->save();
						}
						
						//random dengan proxy yang lain
						$arr_proxy_login = $this->random_proxy();
					}
					else if ( intval(session('attempt_count')) >= 3 ) {
						//flag proxy supaya ga dipake lagi hari itu
						$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
														->where("port",$arr_proxy_login["port"])
														->where("cred",$arr_proxy_login["cred"])
														->first();
						if(!is_null($proxy_login)){
							$proxy_login->is_error = 1;
							$proxy_login->save();
						}
						
						$arr["message"] = "Mohon maaf, Anda telah melebihi batas login attempt 2x untuk menghindari akun anda di flag oleh Instagram Silahkan coba kembali setelah 3 x 24 jam";
						$arr["type"] = "error";
						return $arr;
					}
					
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => intval(session('attempt_count')) + 1 ,
					]);
				}
				else {
					$arr_proxy_login = $this->random_proxy();
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => 0,
					]);
				}
				if($arr_proxy_login["cred"]==""){
					$i->setProxy("http://".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"]);
				}
				else {
					$i->setProxy("http://".$arr_proxy_login["cred"]."@".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"]);
				}
				
				// $i->setUser(strtolower($array_data["username"]), $array_data["password"]);
				$i->login(Request::input("username"), Request::input("password"), 1800);

			} 
			catch (\InstagramAPI\Exception\IncorrectPasswordException $e) {
				//klo error password
				$is_error = 1 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\AccountDisabledException $e) {
				//klo error password
				$is_error = 1 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\CheckpointRequiredException $e) {
				//klo error email / phone verification 
				$is_error = 2 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\ChallengeRequiredException $e) {
				$is_error = 2 ;
				$error_message = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\BadRequestException $e) {
				$arr["message"]= "Silahkan turn off, 2 Factor Authentication";
				$arr["type"]= "error";
				return $arr;
			}
			catch (\InstagramAPI\Exception\ThrottledException $e) {
				$arr["message"]= "Server is busy now, silahkan coba lagi 5 menit kemudian.";
				$arr["type"]= "error";
				return $arr;
			}
			catch (Exception $e) {
				$error_message = $e->getMessage();
				// $response_error = $e->getResponse();
				// $trace_string = $e->getTraceAsString();
				
				// if ($error_message == "InstagramAPI\Response\LoginResponse: The password you entered is incorrect. Please try again.") {
				if (strpos($error_message, 'InstagramAPI\Response\LoginResponse:') !== false) {
					$is_error = 1 ;
					$error_message = $e->getMessage();
				} 
				if ( ($error_message == "InstagramAPI\Response\LoginResponse: Challenge required.") || ( substr($error_message, 0, 18) == "challenge_required") || ($error_message == "InstagramAPI\Response\TimelineFeedResponse: Challenge required.") || ($error_message == "InstagramAPI\Response\LoginResponse: Sorry, there was a problem with your request.") ){
					$is_error = 2 ;
					$error_message = $e->getMessage();
				}
				if ( (strpos($e->getMessage(), 'Network: CURL error') !== false) || (strpos($e->getMessage(), 'No response from server') !== false) ) {
					//proxy ga bisa, flag proxy supaya ga dipake lagi hari itu
					$proxy_login = ProxyLogin::where("proxy",$arr_proxy_login["proxy"])
													->where("port",$arr_proxy_login["port"])
													->where("cred",$arr_proxy_login["cred"])
													->first();
					if(!is_null($proxy_login)){
						$proxy_login->is_error = 2;
						$proxy_login->save();
					}
					
					$arr_proxy_login = $this->random_proxy();
					session([
						'proxy' => collect($arr_proxy_login),
						'attempt_count' => 0,
					]);
					
					$arr["message"] = "Network error, silahkan coba lagi";
					$arr["type"] = "error";
					return $arr;
				}
			}

			
		if ($is_error == 1) {
			$arr["message"]= "Instagram Login is not valid";
			// $arr["error_message"]= $error_message;
			// $arr["tracestring"]= $trace_string;
			// $arr["response_error"]= $response_error;
			$arr["type"]= "error";
			return $arr;
		}
		else if ($is_error == 2) {
			$arr["message"]= "Error Confirmation";
			// $arr["error_message"]= $error_message;
			// $arr["tracestring"]= $trace_string;
			// $arr["response_error"]= $response_error;
      $dt = Carbon::now();
			$user_log = new UserLog;
			$user_log->email = $user->email." ".Request::input("username");
			$user_log->admin = "";
			$user_log->description = "Error Proxy : ".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"].":".$arr_proxy_login["cred"];
			$user_log->created = $dt->toDateTimeString();
			$user_log->save();
			$arr["type"]= "error2";
			return $arr;
		}
		else if ($is_error == 0) {
			$setting = Setting::find($setting_id_temp);
			$setting->error_cred = 0;
			$setting->save();
			SettingMeta::createMeta("error_message_cred","",$setting_id_temp);
		}

      $dt = Carbon::now();
			$user_log = new UserLog;
			$user_log->email = $user->email." ".Request::input("username");
			$user_log->admin = "";
			$user_log->description = "Success add Proxy : ".$arr_proxy_login["proxy"].":".$arr_proxy_login["port"].":".$arr_proxy_login["cred"]." Proxy activity : ".$arr_proxy["proxy"].":".$arr_proxy["port"].":".$arr_proxy["cred"];
			$user_log->created = $dt->toDateTimeString();
			$user_log->save();
			
			session([
				'proxy' => collect($arr_proxy_login),
				'attempt_count' => 0 ,
			]);
		
		/*} else if ($user->test==2){

			$ig_data = Setting::get_ig_data(Request::input("username"));			
			if (!$ig_data["found"]) {
			// if (!$found) {
				$arr["message"]= "Instagram username not found";
				$arr["type"]= "error";
				return $arr;
			}
		}*/
		
		if ($user->link_affiliate<> "") {
			$ig_data = Setting::get_ig_data(Request::input("username"));
			if ($ig_data["found"]) {
				$setting = Setting::where("insta_user_id","=",$ig_data["id"])->where("type","=","temp")->first();
				if ( !is_null($setting) ) {
					if ( ($setting->is_active == 1) && ($user->id <> $setting->last_user) ) {
						$arr["message"]= "Account sudah pernah terdaftar sebelumnya, untuk MELANJUTKAN silahkan BERLANGGANAN terlebih dahulu";
						$arr["type"]= "error";
						return $arr;
					}
				}
			}
		}
		
		
    $setting = Setting::where("insta_username","=",Request::input("username"))
								->where("type","=","temp")
								->where("is_active","=",1)
								->first();
    if (is_null($setting)) {
			//update create new 
			$setting = Setting::find($setting_id_temp);
			if(!is_null($setting)) {
				//active in
				$setting->is_active = 1;
				$setting->save();
				
				if ($setting->status=="deleted") {
					$setting->status = 'stopped';
					$setting->user_id = $user->id;
					$setting->last_user = $user->id;
					$setting->insta_password = Request::input("password");
					$setting->error_cred = 0;
					$setting->save();

					$setting_helper = SettingHelper::where("setting_id","=",$setting->id)->first();
					if (!is_null($setting_helper)) {
						$setting_helper->cookies = "";
						$setting_helper->save();
					}
					
					$linkUserSetting = new LinkUserSetting;
					$linkUserSetting->user_id = $user->id;
					$linkUserSetting->setting_id = $setting->id;
					$linkUserSetting->save();
				}
			}
    } 
		else {
      $linkUserSetting = LinkUserSetting::where("setting_id","=",$setting->id)
                          ->first();
      if (!is_null($linkUserSetting)) {
        if ($linkUserSetting->user_id <> $user->id) {
          $arr["message"]= "Instagram username sudah digunakan";
          $arr["type"]= "error";
          return $arr;
        }
        
        if (  ($linkUserSetting->user_id == $user->id) && ( ($setting->status=="stopped") || ($setting->status=="started") )  ) {
					$arr["message"]= "Account anda sudah terdaftar";
					$arr["type"]= "error";
					return $arr;
				}
				
      } 
			else {
        $linkUserSetting = new LinkUserSetting;
        /* old one
				$linkUserSetting->user_id = $user->id;
        $linkUserSetting->setting_id = $setting->id;
        $linkUserSetting->save();*/
			}
			/* new one */
			$linkUserSetting->user_id = $user->id;
			$linkUserSetting->setting_id = $setting->id;
			$linkUserSetting->save();

      if ($setting->status=="deleted") {
        $setting->status = 'stopped';
        $setting->user_id = $user->id;
        $setting->last_user = $user->id;
        $setting->insta_password = Request::input("password");
        $setting->error_cred = 0;
        $setting->save();

        // $setting_temp = Setting::post_info_admin($setting->id);
      }
			
			//buatkan setting helper di assignkan ke A1 - new klo delete, add account lagi
			//klo belum ada setting helper 
			$setting_helper = SettingHelper::where("setting_id","=",$setting->id)->first();
			if (is_null($setting_helper)) {
				$setting_helper = new SettingHelper;
				$setting_helper->setting_id = $setting->id;
				$setting_helper->use_automation = 1;
				$setting_helper->server_automation = "AA7(automation-7)";
				$setting_helper->save();
			} 
			else {
				$setting_helper->cookies = "";
				$setting_helper->save();
			}
			
			//update user-id 
			if ($setting->insta_user_id == "0"){
				// $ig_data = Setting::get_ig_data($setting->insta_username,$setting->id);
				$ig_data = Setting::get_ig_data($setting->insta_username);
				$id = $ig_data["id"];
				
				$setting->insta_user_id = $id;
				$setting->save();
			}
			
			
			
    }

		//create log 
		$dt = Carbon::now()->setTimezone('Asia/Jakarta');
		$settingLog = new SettingLog;
		$settingLog->setting_id = $setting_id_temp;
		$settingLog->status = "ADD Account";
		$settingLog->description = "settings log";
		$settingLog->created = $dt->toDateTimeString();
		$settingLog->save();
		
		
    return $arr;
  }
  
  public function load_account(){  
    $user = Auth::user();

    $datas = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
              ->where("is_active","=",1)
              ->where("status","!=","deleted")
              ->get();

		$account_active = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
              ->select("settings.*")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("type","=","temp")
							->where("is_active","=",1)
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
		return "";
    $user = Auth::user();

    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
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
		

		$categories = Category::all();
		
		$strCategory = "";
		foreach ($categories as $category) {
			$strCategory .= json_encode(
								array(
									"class"=>strtolower($category->categories),
									"value"=>strtolower($category->name),
									"name"=>ucfirst($category->name),
								)).",";
		}
		
		$strClassCategory = "";
		$groupCategories = Category::groupBy('categories')->get();
		foreach ($groupCategories as $groupCategory) {
			$strClassCategory .= json_encode(
								array(
									"value"=>strtolower($groupCategory->categories),
									"label"=>ucfirst($groupCategory->categories),
								)).",";
		}
		
		$ads_content = "";
		$post = Post::where("type","=","ads")->first();
		if (!is_null($post)) {
			$ads_content = $post->description;
		}

		//get response from 
		// $inboxResponse = json_decode($link->messages);	
		try {
			$i = new Instagram(false,false,[
				"storage"       => "mysql",
				"dbhost"       => Config::get('automation.DB_HOST'),
				"dbname"   => Config::get('automation.DB_DATABASE'),
				"dbusername"   => Config::get('automation.DB_USERNAME'),
				"dbpassword"   => Config::get('automation.DB_PASSWORD'),
			]);
			
			$i->setUser(strtolower($link->insta_username), $link->insta_password);
			$proxy = Proxies::find($link->proxy_id);
			if (!is_null($proxy)) {
				if($proxy->cred==""){
					$i->setProxy("http://".$proxy->proxy.":".$proxy->port);					
				}
				else {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
			}
			
			//ga dipake 
			// $i->login();
			$i->login(strtolower($link->insta_username), $link->insta_password, 1800);
			$inboxResponse = $i->getV2Inbox();
			$pendingInboxResponse = $i->getPendingInbox();
		}
		catch (Exception $e) {
			return $e->getMessage();
		}
		
    return view("member.auto-manage.account-setting")->with(array(
      'user'=>$user,
      'settings'=>$link,
      'view_timeperaccount'=>$view_timeperaccount,
      'view_totaltime'=>$view_totaltime,
      'strCategory'=>$strCategory,
      'strClassCategory'=>$strClassCategory,
      'ads_content'=>$ads_content,
      'inboxResponse'=>$inboxResponse,
      'pendingInboxResponse'=>$pendingInboxResponse,
      ));
  }

  public function action_direct_message(){  
		$arr["type"]="success";
		
		/* ga jadi dipake, karena langsung disend(NO QUEUE)
		$message = new SettingHelper;
		$message->setting_id = Request::input("setting_id");
		$message->pk_id = Request::input("pk_id");
		$message->send_text = Request::input("message");
		$message->send_text_timestamp = 0;
		$message->is_done = 0;
		$message->save();
		*/
		
		try {
			$setting = Setting::join("setting_helpers","setting_helpers.setting_id","=","settings.id")
									->where("settings.id",Request::input("setting_id"))
									->first();
			$i = new Instagram(false,false,[
				"storage"       => "mysql",
				"dbhost"       => Config::get('automation.DB_HOST'),
				"dbname"   => Config::get('automation.DB_DATABASE'),
				"dbusername"   => Config::get('automation.DB_USERNAME'),
				"dbpassword"   => Config::get('automation.DB_PASSWORD'),
			]);
			
			$i->setUser(strtolower($setting->insta_username), $setting->insta_password);
			$proxy = Proxies::find($setting->proxy_id);
			if (!is_null($proxy)) {
				if($proxy->cred==""){
					$i->setProxy("http://".$proxy->proxy.":".$proxy->port);					
				}
				else {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
			}
			
			//ga dipake
			// $i->login();
			$i->login(strtolower($setting->insta_username), $setting->insta_password, 1800);
			if ( Request::input("type") == "message" ) {
				$i->directMessage(Request::input("pk_id"), Request::input("message"));
			}
			else if ( Request::input("type") == "like" ) {
				$i->directMessage(Request::input("pk_id"), Request::input("message"));
			}
			$listMessageResponse = $i->directThread(Request::input("thread_id"));

			$arr["resultEmailData"] = view("member.auto-manage.message-inbox")->with(array(
																			'listMessageResponse'=>$listMessageResponse,
																			'setting_id'=>Request::input("setting_id"),
																			'thread_id'=>Request::input("thread_id"),
																		))->render();
		}
		catch (Exception $e) {
			$arr["type"]="error";
		}
		
		return $arr;
	}
	
  public function check_message(){  
		$arr["type"]="success";
		
		try {
			$setting = Setting::join("setting_helpers","setting_helpers.setting_id","=","settings.id")
									->where("settings.id",Request::input("setting_id"))
									->first();
			$i = new Instagram(false,false,[
				"storage"       => "mysql",
				"dbhost"       => Config::get('automation.DB_HOST'),
				"dbname"   => Config::get('automation.DB_DATABASE'),
				"dbusername"   => Config::get('automation.DB_USERNAME'),
				"dbpassword"   => Config::get('automation.DB_PASSWORD'),
			]);
			
			$i->setUser(strtolower($setting->insta_username), $setting->insta_password);
			$proxy = Proxies::find($setting->proxy_id);
			if (!is_null($proxy)) {
				if($proxy->cred==""){
					$i->setProxy("http://".$proxy->proxy.":".$proxy->port);					
				}
				else {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
			}
			
			//ga dipake
			// $i->login(false,300);
			$i->login(strtolower($setting->insta_username), $setting->insta_password, 300);
			$listMessageResponse = $i->directThread(Request::input("thread_id"));
			// $arr["listMessageResponse"] = json_encode($listMessageResponse);
			$arr["resultEmailData"] = view("member.auto-manage.message-inbox")->with(array(
																			'listMessageResponse'=>$listMessageResponse,
																			'setting_id'=>Request::input("setting_id"),
																			'thread_id'=>Request::input("thread_id"),
																		))->render();
		}
		catch (Exception $e) {
			$arr["type"]="error";
			$arr["resultEmailData"] = "error";
		}
		
		return $arr;
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

		if (!$data["status_auto"]) {
			// deleted comment if ( ( ($data['status_comment']=="on") || ($data['status_like']=="on") ) || (($data["follow_source"]=="hashtags") && ($data['status_follow_unfollow']=="on") ) ) {
			if ( ($data['status_like']=="on")  || (($data["follow_source"]=="hashtags") && ($data['status_follow_unfollow']=="on") ) ) {
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
			
			if ( ($data['max_follow']>7000) && ($data['status_follow_unfollow']=="on") ){
				$arr["message"]= "Max follow tidak boleh lebih besar 7000";
				$arr["type"]= "error";
				return $arr;
			}
				
			if ( ($data['max_follow']<1000)&& ($data['status_follow_unfollow']=="on") ){
				$arr["message"]= "Max follow tidak boleh lebih kecil 1000";
				$arr["type"]= "error";
				return $arr;
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
			
			/*deleted comment
			if ($data['status_comment']=="on") {
				// if ( (strpos($data['comments'], '<@owner>') !== false) && (strpos($data['comments'], '{') !== false) && (strpos($data['comments'], '}')!==false) ) {
				if ( (strpos($data['comments'], '{') !== false) && (strpos($data['comments'], '}')!==false) ) {
				} else {
					$arr["message"]= "Comments memerlukan spin comment";
					$arr["type"]= "error";
					return $arr;
				}
				$pieces = explode(";",$data["comments"]);
				if (count($pieces)<5) {
					$arr["message"]= "Anda harus membuat Min 5 Kalimat. Agar lebih variatif & membuat akun anda aman di Instagram. ";
					$arr["type"]= "error";
					return $arr;
				}
				
			}*/
			
			//deleted comment if ( ($setting_temp->status=="started") && ($data['status_follow_unfollow']=="off") && ($data['status_like']=="off") && ($data['status_comment']=="off") ) {
			if ( ($setting_temp->status=="started") && ($data['status_follow_unfollow']=="off") && ($data['status_like']=="off") ) {
				$arr["message"]= "Silahkan pilih activity follow / like ";
				$arr["type"]= "error";
				return $arr;
			}

			//blacklist & whitelist
			if ($data["status_blacklist"])   {
				if ($data["usernames_blacklist"]=="") {
					$arr["message"]= "Blacklist usernames anda masih 0";
					$arr["type"]= "error";
					return $arr;
				}
			}
			if ($data["status_whitelist"])   {
				if ($data["usernames_whitelist"]=="") {
					$arr["message"]= "Whitelist usernames anda masih 0";
					$arr["type"]= "error";
					return $arr;
				}
			}
		}
		
		if ( ($data["is_auto_get_likes"]) || ($data["status_auto"]) ) {
			//cek private, untuk yang full auto atau advanced manual tapi auto get like dipilih
			if ($this->check_private_user($setting_temp->insta_username)) {
				$arr["message"]= "Profile account instagram tidak boleh di Private, untuk fitur auto get like";
				$arr["type"]= "error";
				return $arr;
			}
			
			$data["is_auto_get_likes"] = 1;
			
			if ($data["status_auto"]) {
				$target_arr = explode(";",$data["target_categories"]);
				if (count($target_arr)>10) {
					$arr["message"] = "Target Categories tidak boleh lebih dari 10 ";
					$arr["type"] = "error";
					return $arr;
				}
				if ($data["target_categories"]=="") {
					$arr["message"] = "Target Categories tidak boleh 0 ";
					$arr["type"] = "error";
					return $arr;
				}
			
				// update hashtags auto , berdasarkan target category 
				$hashtags_auto = "";
				$counter = 1;
				foreach ($target_arr as $target_data) {
					$category = Category::where("name","like","%".$target_data."%")->first();
					if ($counter<count($target_arr)) {
						$hashtags_auto .= $category->hashtags.";"; 
					} else {
						$hashtags_auto .= $category->hashtags; 
					}
					$counter += 1;
				}
				$setting_temp->hashtags_auto = $hashtags_auto;
				$setting_temp->save();
				
				//default value klo full auto (auto follow, like)
				/*
				$data["activity"] = "follow";
				$data["status_follow_unfollow"] = "on";
				$data["status_follow"] = "on";
				$data["status_unfollow"] = "off";
				*/
				$data["status_follow_auto"] = 1;
				$data["status_unfollow_auto"] = 0;
			}
		}
		
		//change auto get likes
		$setting_helper = SettingHelper::where("setting_id","=",$setting_temp->id)->first();
		if (!is_null($setting_helper)) {
			$setting_helper->is_auto_get_likes = $data["is_auto_get_likes"] ;
			$setting_helper->number_likes = 30 ;
			if ( ($data["is_auto_get_likes"]) && ($setting_helper->identity=="") ) {
				$setting_helper->identity = "none" ;
			}
			if ($data["status_auto"]) {
				$setting_helper->is_auto_get_likes = 1 ;
				$setting_helper->target = $data["target_categories"] ;
			}
			$setting_helper->save();
		}
		
		// $following = intval (SettingMeta::getMeta($setting_temp->id,"following"));
		$following = $setting_temp->num_of_following;
		if (($following>7000 ) && ($data["activity"]=="follow") ) {
			if (!$data["status_auto"]) {
				$arr["message"]= "Tidak dapat melakukan activity following";
				$arr["type"]= "error";
				return $arr;
			}
			if ($data["status_auto"]) {
				/*
				$data["activity"] = "unfollow";
				$data["status_follow"] = "off";
				$data["status_unfollow"] = "on";
				*/
				$data["status_follow_auto"] = 0;
				$data["status_unfollow_auto"] = 1;
			}
		}
		
		
		//hapus pesan auto unfollow 
		// SettingMeta::createMeta("auto_unfollow","no",$setting_temp->id);
		$setting_temp->is_auto_unfollow = 0;
		$setting_temp->save();
		
		//hapus space di hashtags
		$data["hashtags"] = str_replace(" ","",$data["hashtags"]);
		$data["hashtags"] = str_replace("@","",$data["hashtags"]);
		$data["hashtags"] = str_replace("#","",$data["hashtags"]);
		
		//hapus space di username
		$data["username"] = str_replace(" ","",$data["username"]);
	
    if (isset($data['dont_comment_su'])) { $data['dont_comment_su'] = 1; } else { $data['dont_comment_su'] = 0; }
    if (isset($data['unfollow_wdfm'])) { $data['unfollow_wdfm'] = 1; } else { $data['unfollow_wdfm'] = 0; }
		
		
		/* 
		* NEW -> is do activity on that day
		* klo ngga follow, like maka ambil dari data yang sebelumnya
		*
		*/
		// if ($data['status_follow_unfollow']=="on") {
			if (isset($data['is_sunday_follow'])) { $data['is_sunday_follow'] = 1; } else { $data['is_sunday_follow'] = 0; }
			if (isset($data['is_monday_follow'])) { $data['is_monday_follow'] = 1; } else { $data['is_monday_follow'] = 0; }
			if (isset($data['is_tuesday_follow'])) { $data['is_tuesday_follow'] = 1; } else { $data['is_tuesday_follow'] = 0; }
			if (isset($data['is_wednesday_follow'])) { $data['is_wednesday_follow'] = 1; } else { $data['is_wednesday_follow'] = 0; }
			if (isset($data['is_thursday_follow'])) { $data['is_thursday_follow'] = 1; } else { $data['is_thursday_follow'] = 0; }
			if (isset($data['is_friday_follow'])) { $data['is_friday_follow'] = 1; } else { $data['is_friday_follow'] = 0; }
			if (isset($data['is_saturday_follow'])) { $data['is_saturday_follow'] = 1; } else { $data['is_saturday_follow'] = 0; }
		// }
		
		// if ($data['status_like']=="on") {
			if (isset($data['is_sunday_like'])) { $data['is_sunday_like'] = 1; } else { $data['is_sunday_like'] = 0; }
			if (isset($data['is_monday_like'])) { $data['is_monday_like'] = 1; } else { $data['is_monday_like'] = 0; }
			if (isset($data['is_tuesday_like'])) { $data['is_tuesday_like'] = 1; } else { $data['is_tuesday_like'] = 0; }
			if (isset($data['is_wednesday_like'])) { $data['is_wednesday_like'] = 1; } else { $data['is_wednesday_like'] = 0; }
			if (isset($data['is_thursday_like'])) { $data['is_thursday_like'] = 1; } else { $data['is_thursday_like'] = 0; }
			if (isset($data['is_friday_like'])) { $data['is_friday_like'] = 1; } else { $data['is_friday_like'] = 0; }
			if (isset($data['is_saturday_like'])) { $data['is_saturday_like'] = 1; } else { $data['is_saturday_like'] = 0; }
		// }
		

		//update supaya blacklist di scrape lagi username id nya
		$data["array_id_blacklist"]="";
		$data["array_id_whitelist"]="";
		
    $setting_temp->update($data);

    
		//for automation purpose
		$setting_helper = SettingHelper::where("setting_id","=",$setting_temp->id)->first();
		if (!is_null($setting_helper)) {
			$setting_helper->is_refresh = 1;
			$setting_helper->save();
		}
		

    // $setting_temp = Setting::post_info_admin($setting_temp->id);
		
		
		//create log 
		$dt = Carbon::now()->setTimezone('Asia/Jakarta');
		$settingLog = new SettingLog;
		$settingLog->setting_id = $setting_temp->id;
		$settingLog->status = "save setting";
		$settingLog->description = "settings log";
		$settingLog->created = $dt->toDateTimeString();
		$settingLog->save();

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
				if ($setting_temp->is_active) {
					if ($setting_temp->error_cred==1) {
						$url = url('dashboard');
						$arr["message"]= "Anda tidak dapat menjalankan program, silahkan update login credential account anda <a href='".$url."'>disini</a>";
						$arr["type"]= "error";
						return $arr;
					}
					if (Request::input('action')=='start') {
						// deleted comment if ( (!$setting_temp->status_auto)&&($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off")&&($setting_temp->status_comment=="off") ) {
						if ( (!$setting_temp->status_auto)&&($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off") ) {
							$arr["message"]= "Follow, Like anda sudah OFF, silahkan di set ON kembali untuk melanjutkan aktifitas ".$setting_temp->insta_username;
							$arr["type"]= "error";
							return $arr;
						}
						$setting_temp->status = "started";
						$setting_temp->start_time = $dt->toDateTimeString();
						$setting_temp->running_time = $dt->toDateTimeString();
						
						//for automation purpose
						$setting_helper = SettingHelper::where("setting_id","=",$setting_temp->id)->first();
						if (!is_null($setting_helper)) {
							$setting_helper->cookies = "";
							$setting_helper->save();

							// ONLY for init assign proxy
							if ($setting_helper->proxy_id == 0) {
								GlobalHelper::clearProxy(serialize($setting_temp), "new");
							}
							
							// codingan tambal an buat cover proxy id yang sudah dihapus
							// dicariin proxy
							$full_proxy =  Proxies::find($setting_helper->proxy_id);
							if (is_null($full_proxy)) {
								$arr_proxy = $this->get_proxy_id($setting_temp->insta_username); //
								$setting_helper->proxy_id = $arr_proxy["proxy_id"];
								$setting_helper->save();
							}
							
						}
						
					}

					if (Request::input('action')=='stop') {
						$setting_temp->status = "stopped";
					}
					$setting_temp->save();

					//create log 
					$dt = Carbon::now()->setTimezone('Asia/Jakarta');
					$settingLog = new SettingLog;
					$settingLog->setting_id = $setting_temp->id;
					$settingLog->status = Request::input('action')." all setting";
					$settingLog->description = "settings log";
					$settingLog->created = $dt->toDateTimeString();
					$settingLog->save();

					// $setting_temp = Setting::post_info_admin($setting_temp->id);
				}
				else {
					$arr["message"]= "Pastikan anda telah menambahkan IG account anda terlebih dahulu. Kemudian START kembali ".$setting_temp->insta_username;
					$arr["type"]= "error";
					return $arr;
				}
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
				if ($setting_temp->is_active) {
					if ($setting_temp->error_cred==1) {
						$url = url('dashboard');
						$arr["message"]= "Anda tidak dapat menjalankan program, silahkan update login credential account anda <a href='".$url."'>disini</a>";
						$arr["type"]= "error";
						return $arr;
					}
					if (Request::input('action')=='start') {
						// deleted comment if ( (!$setting_temp->status_auto)&&($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off")&&($setting_temp->status_comment=="off") ) {
						if ( (!$setting_temp->status_auto)&&($setting_temp->status_follow_unfollow=="off")&&($setting_temp->status_like=="off") ) {
							$arr["message"]= "Anda tidak dapat menjalankan program, silahkan pilih aktifitas yang akan dilakukan (follow/like). Jangan lupa di SAVE sesudahnya. ";
							$arr["type"]= "error";
							return $arr;
						}
						$setting_temp->status = "started";
						$setting_temp->start_time = $dt->toDateTimeString();
						$setting_temp->running_time = $dt->toDateTimeString();

						//for automation purpose
						$setting_helper = SettingHelper::where("setting_id","=",$setting_temp->id)->first();
						if (!is_null($setting_helper)) {
							// $setting_helper->cookies = "";
							// $setting_helper->save();

							// ONLY for init assign proxy
							if ($setting_helper->proxy_id == 0) {
								$setting_helper->cookies = ""; //trying to fixing error "ubah setting instagram anda"
								$setting_helper->save();
								GlobalHelper::clearProxy(serialize($setting_temp),"new");
							}
						}
						
						// codingan tambal an buat cover proxy id yang sudah dihapus
						// dicariin proxy
						$full_proxy =  Proxies::find($setting_helper->proxy_id);
						if (is_null($full_proxy)) {
							$arr_proxy = $this->get_proxy_id($setting_temp->insta_username); //
							$setting_helper->proxy_id = $arr_proxy["proxy_id"];
							$setting_helper->save();
						}
							
					}

					if (Request::input('action')=='stop') {
						$setting_temp->status = "stopped";
					}
					$setting_temp->save();

					//create log 
					$dt = Carbon::now()->setTimezone('Asia/Jakarta');
					$settingLog = new SettingLog;
					$settingLog->setting_id = $setting_temp->id;
					$settingLog->status = Request::input('action')." setting";
					$settingLog->description = "settings log";
					$settingLog->created = $dt->toDateTimeString();
					$settingLog->save();
						
					// $setting_temp = Setting::post_info_admin($setting_temp->id);
				}
				else {
					$arr["message"]= "Pastikan anda telah menambahkan IG account anda terlebih dahulu. Kemudian START kembali ".$setting_temp->insta_username;
					$arr["type"]= "error";
					return $arr;
				}
      }
    }

		$timeLog = new TimeLog;
		$timeLog->user_id = $user->id;
		$timeLog->time = $user->active_auto_manage;
		$timeLog->description = "log waktu users, ".Request::input('action');
		$timeLog->created = $dt->toDateTimeString();
		$timeLog->save();
		

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
			
			//delete link 
			$deleteLink = LinkUserSetting::
											where("setting_id","=",$account->id)
											->where("user_id","=",$user->id)
											->delete();
			
			//for automation purpose
			$setting_helper = SettingHelper::where("setting_id","=",$account->id)->first();
			if (!is_null($setting_helper)) {
				//new karena banyak yang error account2 saat didelete
				// $setting_helper->proxy_id = 0 ; 
				// $setting_helper->save();
				
				//new code, supaya klo password ganti bisa di add (next stepnya)
				$user_setting = UserSetting::where("username",$account->insta_username)->delete();
			}
					
			//slug delete 
			$last_hit = Setting::where("insta_user_id","like","delete-%")->orderBy('insta_user_id', 'desc')->first();
			if (is_null($last_hit)) {
				$slug = "delete-00000";
			} else {
				$temp_arr = explode("-", $last_hit->insta_user_id );
				$ctr = intval($temp_arr[1]); $ctr++;
				$slug = "delete-".str_pad($ctr, 5, "0", STR_PAD_LEFT);
			}
			
			// $setting->insta_user_id = $slug;
			$setting->save();
			// $setting_real = Setting::where("insta_username","=",$setting->insta_username)->where("type","=","real")->first();
			// if (!is_null($setting_real)) {
				// $setting_real->insta_user_id = $slug;
				// $setting_real->save();
			// }
			
			//create log 
			$dt = Carbon::now()->setTimezone('Asia/Jakarta');
			$settingLog = new SettingLog;
			$settingLog->setting_id = $setting->id;
			$settingLog->status = "Delete setting";
			$settingLog->description = "settings log";
			$settingLog->created = $dt->toDateTimeString();
			$settingLog->save();

			$timeLog = new TimeLog;
			$timeLog->user_id = $user->id;
			$timeLog->time = $user->active_auto_manage;
			$timeLog->description = "log waktu users, deleted";
			$timeLog->created = $dt->toDateTimeString();
			$timeLog->save();
			
			
			// $setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (Delete IG Account)");
		}
		
		return $arr;
	}
	

	public function agree_terms(){  
		$user = Auth::user();
		$user->agree_term_condition=1;
		$user->save();
		
		return "success";
	}

	public function checking_cred_instagram($username,$password,$arr_proxy,$setting_id = 0){  
	  if (!is_null($arr_proxy)) {
			$port = $arr_proxy["port"];
			$cred = $arr_proxy["cred"];
			$proxy = $arr_proxy["proxy"];
		}
		$auth = true;

		if ($setting_id <> 0) {
			//use own proxy if have
			$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
			if (!is_null($setting_helper)) {
				if ($setting_helper->proxy_id== 0) {
					$arr_proxy = $this->get_proxy_id($username); 
					$setting_helper->proxy_id = $arr_proxy["proxy_id"];
					$setting_helper->save();
				}
				$full_proxy =  Proxies::find($setting_helper->proxy_id);
				if (!is_null($full_proxy)) {
					$port = $full_proxy->port;
					$cred = $full_proxy->cred;
					$proxy = $full_proxy->proxy;
					$auth = $full_proxy->auth;
				}
			}
		}
		
		//check klo uda ada di celebpost maka pake proxy celebpost
		$account = Account::where("username","=",$username)
								->first();
		if (!is_null($account)){
			$proxies = Proxies::find($account->proxy_id);
			if (!is_null($proxies)) {
				$port = $proxies->port;
				$cred = $proxies->cred;
				$proxy = $proxies->proxy;
				$auth = $proxies->auth;
			}
		}
	
		

		$url = "https://www.instagram.com/accounts/login/?force_classic_login";
		if(App::environment() == "local"){		
			$cookiefile = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$username.'-cookiess.txt';
		}
			
		if (file_exists($cookiefile)) {
			unlink($cookiefile);
		}
		
		$c = curl_init();
		if ($auth) {
			curl_setopt($c, CURLOPT_PROXY, $proxy);
			curl_setopt($c, CURLOPT_PROXYPORT, $port);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
		} else if (!$auth) {
			curl_setopt($c, CURLOPT_PROXY, $proxy.":".$port);
		}
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
    if ($auth) {
			curl_setopt($c, CURLOPT_PROXY, $proxy);
			curl_setopt($c, CURLOPT_PROXYPORT, $port);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
		} else if (!$auth) {
			curl_setopt($c, CURLOPT_PROXY, $proxy.":".$port);
		}
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
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
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

	public function check_private_user($username){
		$is_private = false;
		

			//
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10880",
				"username"=>"melodianaelisa",
				"password"=>"qazwsx123",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10881",
				"username"=>"dessiarumi",
				"password"=>"abcde12345",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10882",
				"username"=>"renawilliams222",
				"password"=>"abcde12345",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10883",
				"username"=>"mayyyvitri",
				"password"=>"qwerty12345",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10884",
				"username"=>"marianalaskmi",
				"password"=>"qwerty12345",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10885",
				"username"=>"magdalenapeter96",
				"password"=>"qazwsx123",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10886",
				"username"=>"felysamora",
				"password"=>"abcde12345",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10887",
				"username"=>"nithaasyari",
				"password"=>"qweasdzxc123",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10888",
				"username"=>"thalianasarifernand",
				"password"=>"987456321qwerty",
			];			
			
			$arr_users[] = [
				"proxy"=>"208.115.112.98",
				"port"=>"10889",
				"username"=>"naningtyasa",
				"password"=>"qwerty12345",
			];			
			
			$arr_user = $arr_users[array_rand($arr_users)];
			
			
			
			
			
			$i = new Instagram(false,false,[
				"storage"       => "mysql",
				"dbhost"       => Config::get('automation.DB_HOST'),
				"dbname"   => Config::get('automation.DB_DATABASE'),
				"dbusername"   => Config::get('automation.DB_USERNAME'),
				"dbpassword"   => Config::get('automation.DB_PASSWORD'),
			]);
			$i->setProxy("http://".$arr_user['proxy'].":".$arr_user['port']);
			try {
				$i->login($arr_user["username"], $arr_user["password"], 300);
				$username = str_replace("@", "", $username);
				if (!$i->account->checkUsername($username)->getAvailable()) {
					$userData = $i->people->getInfoByName($username)->getUser();
					if (!is_null($userData)) {
						//new
						$is_private = (int) $userData->getIsPrivate();
					}
				}
			}
			catch (Exception $e) {
			}
			catch (\InstagramAPI\Exception\NotFoundException $e) {
				
			} catch (\InstagramAPI\Exception\EmptyResponseException $e) {
				$this->check_private_user($username);
			} catch (\InstagramAPI\Exception\NetworkException $e) {
				usleep(120000); 
				$this->check_private_user($username);
			}
		
		return $is_private;
	}

	public function backup_check_private_user($username){
		$is_private = false;
		
		/*$ports[] = "1945"; 
		$ports[] = "3128"; 
		$ports[] = "2015"; 
		$ports[] = "2503"; 
		$ports[] = "3103"; 
		$ports[] = "2017"; 
		$port = $ports[array_rand($ports)];
		$cred = "sugiarto123:678flazz";
		$proxy = "103.236.201.60";//good proxy
		$auth = true;*/
		$arr_proxys[] = [
			"proxy"=>"103.31.251.171",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"8080",
		];			
		$arr_proxys[] = [
			"proxy"=>"103.31.251.171",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"9700",
		];			
		$arr_proxys[] = [
			"proxy"=>"103.31.251.171",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"3128",
		];			

		$arr_proxys[] = [
			"proxy"=>"103.31.250.62",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"8080",
		];			
		$arr_proxys[] = [
			"proxy"=>"103.31.250.62",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"9700",
		];			
		$arr_proxys[] = [
			"proxy"=>"103.31.250.62",
			"cred"=>"rizky12:proxyrizky",
			"port"=>"3128",
		];			

		$arr_proxy = $arr_proxys[array_rand($arr_proxys)];
		

		if(App::environment() == "local"){
			$cookiefile = base_path().'/../general/ig-cookies/'.$username.'-cookies-grab.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$username.'-cookies-grab.txt';
		}
			
		$url = "https://www.instagram.com/".$username."/?__a=1";
		$c = curl_init();


		// if ($auth) {
			curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"]);
			curl_setopt($c, CURLOPT_PROXYPORT, $arr_proxy["port"]);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $arr_proxy["cred"]);
		// } else if (!$auth) {
			// curl_setopt($c, CURLOPT_PROXY, $proxy.":".$port);
		// }
		curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_REFERER, $url);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
		$page = curl_exec($c);
		curl_close($c);
		
		$arr = json_decode($page,true);
		// var_dump(json_decode($page,true));
		if (count($arr)>0) {
			if ($arr["graphql"]["user"]["is_private"]) {
				$is_private = true;
			}
			else if (!$arr["graphql"]["user"]["is_private"]) {
				$is_private = false;
			}
			
			// foreach ($arr["user"]["media"]["nodes"] as $data) {
				// echo $data["id"]."   ".$data["code"]."  ".$data["owner"]["id"]."<br>";
			// }
		} else {
			echo "username not found";
		}
		
		
		if (file_exists($cookiefile)) {
			unlink($cookiefile);
		}
		
		return $is_private;
	}

	/*
	* FUNCTION CUMAN JALAN DI PRODUCTION, KARENA VIEW DATABASE
	* is_on_celebgramme tidak dipake 
	*/
	public function get_proxy_id($insta_username){	
		//check insta_username ada di celebpost 
		$check = Account::where("proxy_id","!=",0)
							->where("username","=",$insta_username)
							->first();
		if (!is_null($check)) {
			$arr["proxy_id"] = $check->proxy_id;
		} else {
			//carikan proxy baru, yang available 
			$availableProxy = ViewProxyUses::select("id","proxy","cred","port","auth",DB::raw(									"sum(count_proxy) as countP"))
												->groupBy("id","proxy","cred","port","auth")
												->orderBy("countP","asc")
												->having('countP', '<', 1)
												->get();
			$arrAvailableProxy = array();
			foreach($availableProxy as $data) {
				$check_proxy = Proxies::find($data->id);
				if ($check_proxy->is_error == 0){
					$dataNew = array();
					$dataNew["id"] = $data->id;
					$arrAvailableProxy[] = $dataNew;	
				}
			}
			if (count($arrAvailableProxy)>0) {
				$proxy_id = $arrAvailableProxy[array_rand($arrAvailableProxy)]["id"];
			} else {
				$availableProxy = ViewProxyUses::select("id","proxy","cred","port","auth",DB::raw(									"sum(count_proxy) as countP"))
													->groupBy("id","proxy","cred","port","auth")
													->orderBy("countP","asc")
													->first();
				if (!is_null($availableProxy)) {
					$proxy_id = $availableProxy->id;
				}
			}
			$arr["proxy_id"] = $proxy_id;
			
		}
	
		$full_proxy =  Proxies::find($arr["proxy_id"]);
    // if ($arr["proxy_id"]<350) {
      // $full_proxy =  Proxies::find(1453);
    // }
    // else {
      // $full_proxy =  Proxies::find(1454);
    // }
		if (!is_null($full_proxy)) {
			$arr["port"] = $full_proxy->port;
			$arr["cred"] = $full_proxy->cred;
			$arr["proxy"] = $full_proxy->proxy;
			$arr["auth"] = $full_proxy->auth;
		}
	
		return $arr;
	}
	
	public function random_proxy(){
		//get proxy login from database
		$proxy_logins = ProxyLogin::
										where("is_error",0)
										->get();
		foreach($proxy_logins as $proxy_login){
			$arr_proxys[] = [
				"proxy"=>$proxy_login->proxy,
				"cred"=>$proxy_login->cred,
				"port"=>$proxy_login->port,
			];
		}
		
		if (ProxyLogin::where("is_error",0)->count() <= 2) {
			//notif KALO proxy login uda mau abis
			Mail::queue('emails.pool-proxy-login', $emaildata, function ($message) use ($type_message) {
				$message->from('no-reply@celebgramme.com', 'Celebgramme');
				$message->to(array(
					"michaelsugih@gmail.com",
				));
				$message->cc(array(
					"celebgramme.dev@gmail.com",
				));
				$message->subject("[Celebgramme] Pool Proxy Login mau habis");
			});
		}
		
		$arr_proxy = $arr_proxys[array_rand($arr_proxys)];

		return $arr_proxy;
	}
}
