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
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Meta;
use Celebgramme\Models\Client;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Proxies;
use Celebgramme\Models\Category;
use Celebgramme\Models\SettingLog;
use Celebgramme\Models\TimeLog;
use Celebgramme\Models\Account;
use Celebgramme\Models\Message;

use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\ViewProxyUses;

use Celebgramme\Helpers\GlobalHelper;

use \InstagramAPI\Instagram;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, DB, Config;

class NewDashboardController extends Controller
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
		
		
    return view("new-dashboard.index")->with(array(
      'user'=>$user,
      'order'=>$order,
      'status_server'=>$status_server,
      'content'=>$content,
      ));
	}

	public function setting_index($id){
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
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
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
		
    return view("new-dashboard.setting")->with(array(
      'user'=>$user,
      'settings'=>$link,
      'view_timeperaccount'=>$view_timeperaccount,
      'view_totaltime'=>$view_totaltime,
      'strCategory'=>$strCategory,
      'strClassCategory'=>$strClassCategory,
      'ads_content'=>$ads_content,
		));
	}

	public function dashboard(){
    $user = Auth::user();
		
		$status_server = Meta::where("meta_name","=","status_server")->first()->meta_value;
		
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
			$timeperaccount = $days." Days";
		} else {
			$timeperaccount = $days." Days ".$hours.":".$minutes;
		}
		
    $order = Order::where("order_status","=","pending")->where("user_id","=",$user->id)->where("image",'=','')->first();
		
    return view("new-dashboard.dashboard")->with(array(
      'user'=>$user,
      'status_server'=>$status_server,
      'timeperaccount'=>$timeperaccount,
      'order'=>$order,
    ));
	}
	
  public function load_account(){  
    $user = Auth::user();

    $datas = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->leftJoin("setting_helpers","setting_helpers.setting_id","=","settings.id")
              ->select("settings.*","setting_helpers.proxy_id")
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
		
    return view("new-dashboard.list-account")->with(array(
      'user'=>$user,
      'datas'=>$datas,
      'account_active'=>$account_active,
      'view_timeperaccount'=>$view_timeperaccount,
      ));
  }
	
	//show chat all message dengan / terhadap 1 user
	public function get_chat_all(){
		$arr["type"]="success";
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
    } 
							
		if (!$link->error_cred) {
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
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				$i->login(false,300);
				$chatAll = $i->directThread(Request::input("data_thread_id"));
				// $arr["chatAll"] = json_encode($chatAll);
				
				$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																			'chatAll'=>$chatAll,
																			'setting_id'=>Request::input("setting_id"),
																			'thread_id'=>Request::input("data_thread_id"),
																			'username_user'=> Request::input("data_username"),
																			'data_pic'=> Request::input("data_pic"),
																		))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
		}
		
		return $arr;
	}

  public function action_direct_message(){  
		$arr["type"]="success";
		
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
    } 
		
		if (!$link->error_cred) {
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
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
				
				$i->login(false,300);
				if ( Request::input("type") == "message" ) {
					$i->directMessage(Request::input("pk_id"), Request::input("message"));
				}
				else if ( Request::input("type") == "like" ) {
					$i->directMessage(Request::input("pk_id"), Request::input("message"));
				}
				
				$chatAll = $i->directThread(Request::input("data_thread_id"));

				$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																				'chatAll'=>$chatAll,
																				'setting_id'=>Request::input("setting_id"),
																				'thread_id'=>Request::input("data_thread_id"),
																				'username_user'=> Request::input("data_username"),
																				'data_pic'=> Request::input("data_pic"),
																			))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
		}
		
		return $arr;
	}
	
	public function get_dm_req(){
		$arr["type"]="success";
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
    } 
							
		if (!$link->error_cred) {
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
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				$i->login(false,300);
				$pendingInboxResponse = $i->getPendingInbox();
				
				$arr["resultEmailData"] = view("new-dashboard.DM-req")->with(array(
																			'pendingInboxResponse'=>$pendingInboxResponse,
																		))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
		}
		
		return $arr;
	}

  public function action_dm_req(){  
		$arr["type"]="success";
		
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
    } 
		
		if (!$link->error_cred) {
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
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
				
				$i->login(false,300);
				if ( Request::input("type") == "approve" ) {
					$i->directThreadAction(Request::input("data_thread_id"), "approve");
				}
				else if ( Request::input("type") == "decline" ) {
					$i->directThreadAction(Request::input("data_thread_id"), "decline");
				}
				
				$pendingInboxResponse = $i->getPendingInbox();

				$arr["resultEmailData"] = view("new-dashboard.DM-req")->with(array(
																				'pendingInboxResponse'=>$pendingInboxResponse,
																			))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
		}
		
		return $arr;
	}

	public function get_dm_inbox(){
		$arr["type"]="success";
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
      return redirect('dashboard')->with( 'error', 'Not authorize to access page');
    } 
							
		if (!$link->error_cred) {
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
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				$i->login(false,300);
				$inboxResponse = $i->getV2Inbox();
				
				$arr["resultEmailData"] = view("new-dashboard.DM-inbox")->with(array(
																			'inboxResponse'=>$inboxResponse,
																		))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
		}
		
		return $arr;
	}
	
}
