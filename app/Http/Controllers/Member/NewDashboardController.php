<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as req;

use Celebgramme\Models\RequestModel;
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
use Celebgramme\Models\Account;
use Celebgramme\Models\Message;
use Celebgramme\Models\AutoResponderSetting;

use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\ViewProxyUses;

use Celebgramme\Helpers\GlobalHelper;

use Illuminate\Pagination\LengthAwarePaginator;

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
		$status_server = "";
		$temp = Meta::where("meta_name","=","status_server")->first();
		if (!is_null($temp)) {
			$status_server = $temp->meta_value;
		}
		
		$content = "";
		$post = Post::where("type","=","home_page")->first();
		if (!is_null($post)) {
			$content = $post->description;
		}
		
		
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
              // ->where("settings.id","=",$id)
              ->where("settings.insta_username","=",$id)
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
		
		//check first punya proxy ga
		// ONLY for init assign proxy
		if ($link->proxy_id == 0) {
			$arr_proxy = GlobalHelper::clearProxy(serialize($link), "new");
		}
		else {
			$arr_proxy['proxy_id'] = $link->proxy_id;
		}
		
		//login dulu buat list following
		try {
				$i = new Instagram(false,false,[
					"storage"       => "mysql",
					"dbhost"       => Config::get('automation.DB_HOST'),
					"dbname"   => Config::get('automation.DB_DATABASE'),
					"dbusername"   => Config::get('automation.DB_USERNAME'),
					"dbpassword"   => Config::get('automation.DB_PASSWORD'),
				]);
				
				$proxy = Proxies::find($arr_proxy['proxy_id']);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
		} 
		catch (Exception $e) {
			$arr["type"]="error";
			$arr["resultEmailData"] = $e->getMessage();
		}
		catch (\InstagramAPI\Exception\IncorrectPasswordException $e) {
			//klo error password
			$arr["type"]="error";
			$arr["resultEmailData"] = $e->getMessage();
		}
		catch (\InstagramAPI\Exception\CheckpointRequiredException $e) {
			//klo error email / phone verification 
			$arr["type"]="error";
			$arr["resultEmailData"] = $e->getMessage();
		}
		
		//buat list user following (for whitelist purpose)
		$arr_user_whitelist = array();
		$counter = 0; $end_cursor = "";
		do {  //repeat until get 50 data scrape 
			try {
				if ($counter==0) {
					$userFollowingResponse = $i->people->getSelfFollowing();
				} else if ($counter>0) {
					$userFollowingResponse = $i->people->getSelfFollowing(null,$end_cursor);
				}
			}
			catch (Exception $e) {
				break;
			}
			$counter += 1;
			
			$has_next_page = true;
			if (!is_null($userFollowingResponse->getNextMaxId())) {
				$end_cursor = $userFollowingResponse->getNextMaxId();
			} else {
				$end_cursor = "";
				$has_next_page = false;
			}
			
			if ( count($userFollowingResponse->getUsers()) > 0 ) {
				//hasil scrape disimpan ke textfile
				foreach ($userFollowingResponse->getUsers() as $data) {
					$arr_user_whitelist[] = array(
						"text"=>$data->getUsername(),
						"value"=>$data->getUsername(),
					);

				}

				
			} 
			else if ( count($userFollowingResponse->getUsers()) == 0 ) {
			}
			// usleep(500000); // 1/2 detik
			usleep(120000); 
		} while ( ($has_next_page) );
			
		
		
    return view("new-dashboard.setting")->with(array(
      'user'=>$user,
      'settings'=>$link,
      'view_timeperaccount'=>$view_timeperaccount,
      'view_totaltime'=>$view_totaltime,
      'strCategory'=>$strCategory,
      'strClassCategory'=>$strClassCategory,
      'ads_content'=>$ads_content,
      'arr_user_whitelist'=>json_encode($arr_user_whitelist),
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
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
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
				$chatAll = $i->direct->getThread(Request::input("data_thread_id"));
				// $arr["chatAll"] = json_encode($chatAll);
				
				$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																			'chatAll'=>$chatAll,
																			'setting_id'=>Request::input("setting_id"),
																			// 'thread_id'=>Request::input("data_thread_id"),
																			'username_user'=> Request::input("data_username"),
																			'data_pic'=> Request::input("data_pic"),
																			'i'=> $i,
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
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
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
				if ( Request::input("type") == "message" ) {
					$send_message = $i->direct->sendText(array('users'=>array(Request::input("pk_id"))), Request::input("message"));
					$chat_user_threadId = $send_message->getPayload()->getThreadId();
					
					//Grab database DM inboxnya
					$arr_inbox = json_decode($setting->array_inbox,true);
					$arr_inbox = (array) $arr_inbox;
					// Obtain a list of columns
					foreach ($arr_inbox as $key => $row) {
						if ( strtolower($row['username']) == strtolower(Request::input("data_username")) ) {
							$dt = Carbon::now();
							$date_message = $dt->timestamp;
							$temp_arr_inbox["pure_date"] = (int)$date_message;
							$temp_arr_inbox["date_message1"] = date("l, H:i:s", $date_message);
							$temp_arr_inbox["date_message2"] = date("Y-m-d", $date_message);
							$text_message = Request::input("message");
							if (strlen($text_message)>=42) {
								$text_message = substr($text_message,0,115)." ...";
							}
							$temp_arr_inbox["text_message"] = $text_message;
							$temp_arr_inbox["status_new_message"] = false;
							
							$temp_arr_inbox["user_id"] = $row['user_id'];
							$temp_arr_inbox["username"] = $row['username'];
							$temp_arr_inbox["profile_pic_url"] = $row['profile_pic_url'];
							$temp_arr_inbox["thread_id"] = $row['thread_id'];
							$temp_arr_inbox["pk"] = $row['pk'];

							unset($arr_inbox[$key]);
							array_unshift($arr_inbox, $temp_arr_inbox);
						}
					}
					//Update database DM inboxnya
					$dt = Carbon::now();
					$setting->array_inbox = json_encode($arr_inbox);
					$setting->last_update_inbox = $dt->toDateTimeString();
					$setting->save(); 
				}
				else if ( Request::input("type") == "like" ) {
					$i->direct->sendText(array('users'=>array(Request::input("pk_id"))), Request::input("message"));
				}
				
				// $chatAll = $i->direct->getThread(Request::input("data_thread_id"));
				$chatAll = $i->direct->getThread($chat_user_threadId);

				$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																				'chatAll'=>$chatAll,
																				'setting_id'=>Request::input("setting_id"),
																				// 'thread_id'=>Request::input("data_thread_id"),
																				'username_user'=> Request::input("data_username"),
																				'data_pic'=> Request::input("data_pic"),
																				'i'=> $i,
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
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
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
				$pendingInboxResponse = $i->direct->getPendingInbox();
				
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
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
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
				if ( Request::input("type") == "approve" ) {
					// $i->directThreadAction(Request::input("data_thread_id"), "approve");
					$i->direct->approvePendingThreads( array (Request::input("data_thread_id")) );
					$chatAll = $i->direct->getThread(Request::input("data_thread_id"));
					// $arr["chatAll"] = json_encode($chatAll);
					
					$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																			'chatAll'=>$chatAll,
																			'setting_id'=>Request::input("setting_id"),
																			'thread_id'=>Request::input("data_thread_id"),
																			'username_user'=> Request::input("data_username"),
																			'data_pic'=> Request::input("data_pic"),
																			'i'=> $i,
																		))->render();
					
				}
				else if ( Request::input("type") == "decline" ) {
					// $i->directThreadAction(Request::input("data_thread_id"), "decline");
					$i->direct->declinePendingThreads( array (Request::input("data_thread_id")) );
					$pendingInboxResponse = $i->direct->getPendingInbox();

					$arr["resultEmailData"] = view("new-dashboard.DM-req")->with(array(
																				'pendingInboxResponse'=>$pendingInboxResponse,
																			))->render();
					
				}
				
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
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
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false, 300);
				
				$need_update = false;
				if (is_null($setting->last_update_inbox)) {
					$need_update = true;
				} else {
					$dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $setting->last_update_inbox)->addMinutes(30);
					$dt2 = Carbon::now();
					if ( $dt1->lte($dt2) ) { 
						$need_update = true;
					}
				}
				
				if (Request::input("is_refresh")=="1") {
						$need_update = true;
				}
				
				if ($need_update) {
					$arr_inbox = array(); $counter = 0;
					// if (!is_null($inboxResponse->inbox->oldest_cursor)) {
					$end_cursor = "";
					do {
						$has_next_page = true;
						if ($counter==0) {
							// $inboxResponse = $i->getV2Inbox();
							$inboxResponse = $i->direct->getInbox();
						} else {
							$inboxResponse = $i->direct->getInbox($end_cursor);
						}
						// sleep(3);

						if (!is_null($inboxResponse->getInbox()->getOldestCursor())) {
							$end_cursor = $inboxResponse->getInbox()->getOldestCursor();
						} else {
							//klo null
							$has_next_page = false;
							$end_cursor = "";
						}
						
						//input array data
						if (count($inboxResponse->getInbox()->getThreads()) > 0 ) {
							$counter_respond = 0;
							foreach ($inboxResponse->getInbox()->getThreads() as $data_arr) {
								$date_message = substr($data_arr->getItems()[0]->getTimestamp(),0,10);
								$arr_data["pure_date"] = (int)$date_message;
								$arr_data["date_message1"] = date("l, H:i:s", $date_message);
								$arr_data["date_message2"] = date("Y-m-d", $date_message);
								$text_message = $data_arr->getItems()[0]->getText();
								if (strlen($text_message)>=42) {
									$text_message = substr($text_message,0,50)." ...";
								}
								$arr_data["text_message"] = $this->removeEmoji($text_message);
								//klo ga ada usernya di break
								if ( (is_null($data_arr->getUsers())) || (empty($data_arr->getUsers())) ) {
									continue;
								}
								$status_new_message_temp = false;
								if ($data_arr->getUsers()[0]->getPk() == $data_arr->getItems()[0]->getUserId()) {
									$status_new_message_temp = true;
								}
								$arr_data["status_new_message"] = $status_new_message_temp;
								$arr_data["user_id"] = $data_arr->getItems()[0]->getUserId();
								$arr_data["username"] = $data_arr->getUsers()[0]->getUsername();
								$arr_data["profile_pic_url"] = $data_arr->getUsers()[0]->getProfilePicUrl();
								$arr_data["thread_id"] = $data_arr->getThreadId();
								$arr_data["pk"] = $data_arr->getUsers()[0]->getPk();
								
								$arr_inbox[] = $arr_data;
							}
						}
						$counter += 1;
					} while ($has_next_page);
					$dt = Carbon::now();
					$setting->array_inbox = json_encode($arr_inbox);
					$setting->last_update_inbox = $dt->toDateTimeString();
					$setting->save(); 
					$arr_inbox = json_decode(json_encode($arr_inbox),true);
				} else {
					$arr_inbox = json_decode($setting->array_inbox,true);
				}

				$arr_inbox = (array) $arr_inbox;
				if (count($arr_inbox)>0) {
					if (Request::input("is_sort") == "1") {
						// Obtain a list of columns
						foreach ($arr_inbox as $key => $row) {
								$status_new_message[$key]  = $row['status_new_message'];
								$pure_date[$key] = $row['pure_date'];
						}

						// Add $data as the last parameter, to sort by the common key
						array_multisort($status_new_message, SORT_DESC, $pure_date, SORT_DESC, $arr_inbox);
						/* old method
						usort($arr_inbox, function($a, $b) {
							return $b['status_new_message'] - $a['status_new_message'];
						});*/
					}
					if (Request::input("search") <> "") {
						//ditaruh di array_temp
						$array_temp = array();
						foreach ($arr_inbox as $data){
							$array_temp[] = $data["username"];
						}
						
						//search, grep array_temp
						$input = preg_quote(Request::input("search"), '~'); // don't forget to quote input string!
						$result = preg_grep('~' . $input . '~', $array_temp);
						
						//cek klo ga ada di array_temp didelete
						foreach ($arr_inbox as $key => $value){
							if (!in_array($value["username"],$result)) {
								unset($arr_inbox[$key]);
							}
						}
					}
				}
				$total_data = count($arr_inbox);
				
				//buat pagination
				$page = Request::input('page'); // Get the current page or default to 1, this is what you miss!
				$perPage = 50;
				$offset = ($page * $perPage) - $perPage;
				$totalPage = floor($total_data / $perPage) +1;
				
				$collection = collect($arr_inbox);
				$chunk = $collection->forPage($page, $perPage);
				

				// $itemsForCurrentPage = array_slice($chunk->all(), $offset, $perPage, true);
				// $pagination= new LengthAwarePaginator($itemsForCurrentPage,  count($chunk), // Total items
					// $perPage, // Items per page
					// $page, // Current page
					// ['path' => "", 'query' => ""]
				// );
				$arr_inbox = $chunk->toArray();

				
        //save unseen_count
        $pendingInboxResponse = $i->direct->getPendingInbox();
        SettingMeta::createMeta("unseen_count",$pendingInboxResponse->getInbox()->getUnseenCount(),Request::input("setting_id"));
				
				$arr["resultEmailData"] = view("new-dashboard.DM-inbox")->with(array(
																			// 'inboxResponse'=> $inboxResponse,
																			'arr_inbox'=> $arr_inbox,
																			'pendingInboxResponse'=>$pendingInboxResponse,
																			'page'=>$page,
																			'totalPage'=>$totalPage,
																		))->render();
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\IncorrectPasswordException $e) {
				//klo error password
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
			catch (\InstagramAPI\Exception\CheckpointRequiredException $e) {
				//klo error email / phone verification 
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
			
		}
		
		return $arr;
	}
	
	public function save_welcome_message(){
		$arr["type"] = "success";
		$arr["message"] ="Welcome Message berhasil disimpan";
		
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
			$arr["type"] = "error";
			$arr["message"] = "Not authorize to access page";
			return $arr;
    } 
		
		/*$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}*/
		
		if ( (Request::input("message") == "") && (Request::input("is_auto_responder")) ) {
			$arr["type"] = "error";
			$arr["message"] = "Silahkan input welcome message auto responder";
			return $arr;
		}
		
		if (!Request::input("is_auto_responder")) {
			// $arr["type"] = "error";
			$arr["message"] = "Status OFF, Auto Responder tidak dijalankan";
			// return $arr;
		}
		
		if ( ((strpos(Request::input("message"), '{') !== false) && (strpos(Request::input("message"), '}')!==false)) && (Request::input("is_auto_responder")) ) {
		} else {
			if (Request::input("is_auto_responder")) {
				$arr["message"]= "Welcome Message to new followers memerlukan spin message, sebaiknya spin message anda mengandung lebih dari 250 kombinasi message";
				$arr["type"]= "error";
				return $arr;
			}
		}
		
		//spin combination
		$spin_combination = $this->count_combination(Request::input("message"));
		if ( intval($spin_combination) <= 250 ) {
			$arr["message"]= "Auto Responder memerlukan Spin message <strong>MINIMUM 250 Kombinasi</strong>. <br>Spin message anda SEKARANG hanya = <span style='color:#F44336;font-weight:Bold;'>".intval($spin_combination)." Kombinasi</span>. <br><span style='color:#00b0e4;'>Silahkan TAMBAH Spin Message anda</span>";
			$arr["type"]= "error";
			return $arr;
		}
		
		
		$setting = Setting::find(Request::input("setting_id"));
		$setting->messages = Request::input("message");
		$setting->is_auto_responder = Request::input("is_auto_responder");
		$setting->save();
		
		return $arr;
	}
	
	public function submit_auto_responder(){
    $user = Auth::user();
    $link = LinkUserSetting::join("settings","settings.id","=","link_users_settings.setting_id")
							->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->select("settings.*","setting_helpers.proxy_id")
              ->where("link_users_settings.user_id","=",$user->id)
              ->where("settings.id","=",Request::input("setting_id"))
              ->where("type","=","temp")
              ->first();
    if (is_null($link)) {
			$arr["type"] = "error";
			$arr["message"] = "Not authorize to access page";
			return $arr;
    } 
		
		/*$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}*/
		
		//checking ga bole dalam hari yang sama, maximal 5
		$auto_responder = AutoResponderSetting::where("setting_id",Request::input("setting_id"))->count();
		if ( ($auto_responder >= 5) && (Request::input("id-auto-responder")=="new") ) {
			$arr["type"] = "error";
			$arr["message"] = "Auto responder tidak boleh lebih dari 5";
			return $arr;
		}
		/*$auto_responder = AutoResponderSetting::where("setting_id",Request::input("setting_id"))->get();
		foreach($auto_responder as $data ) {
			if ($data->num_of_day == Request::input("num_of_day")) {
				if (Request::input("id-auto-responder")<>$data->id) {   
					$arr["type"] = "error";
					$arr["message"] = "Hari ke - ".Request::input("num_of_day")." sudah ada pada database, silahkan edit data";
					return $arr;
				}
			}
		}*/
		
		if ((strpos(Request::input("message_responder"), '{') !== false) && (strpos(Request::input("message_responder"), '}')!==false)) {
		} else {
			$arr["message"]= "Auto Responder memerlukan spin message, sebaiknya spin message anda mengandung lebih dari 250 kombinasi message";
			$arr["type"]= "error";
			return $arr;
		}
		
		//spin combination
		$spin_combination = $this->count_combination(Request::input("message_responder"));
		if ( intval($spin_combination) <= 250 ) {
			$arr["message"]= "Auto Responder memerlukan Spin message <strong>MINIMUM 250 Kombinasi</strong>. <br>Spin message anda SEKARANG hanya = <span style='color:#F44336;font-weight:Bold;'>".intval($spin_combination)." Kombinasi</span>. <br><span style='color:#00b0e4;'>Silahkan TAMBAH Spin Message anda</span>";
			$arr["type"]= "error";
			return $arr;
		}
		
		if (Request::input("num_of_day") < 1){
			$arr["message"]= "Next Messages harus minimum 1 hari sesudah auto reply pertama";
			$arr["type"]= "error";
			return $arr;
		}
		
			
    if (Request::input("id-auto-responder")=="new") {
      $arr["message"] = "Proses add berhasil dilakukan";
      $auto_responder = new AutoResponderSetting;
    } else {
      $arr["message"] = "Proses edit berhasil dilakukan";
      $auto_responder = AutoResponderSetting::find(Request::input("id-auto-responder"));
    }
    $auto_responder->message = Request::input("message_responder");
    $auto_responder->num_of_day = Request::input("num_of_day");
    $auto_responder->setting_id = Request::input("setting_id");
    $auto_responder->save();

    $arr['type'] = 'success';
    $arr['id'] = Request::input("id-auto-responder");
    return $arr;    
	}

	public function get_auto_responder(){
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
			
		/*$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}*/
			
		$auto_responder_setting = AutoResponderSetting::where("setting_id",Request::input("setting_id"))
															->orderBy('num_of_day', 'asc')
															->get();

		
		$arr["resultData"] = view("new-dashboard.auto-responder")->with(array(
																	'auto_responder_setting'=>$auto_responder_setting,
																))->render();
		
		return $arr;
	}
	
	public function delete_auto_responder(){
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
		
		/*$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
      return redirect('dashboard')->with( 'error', 'Setting account belum distart');
		}*/
							
		$arr["message"] = "Proses delete berhasil dilakukan";
		$auto_responder = AutoResponderSetting::find(Request::input("id"))->delete();
		
		
		return $arr;
	}
	
	public static function removeEmoji($text) {

    $clean_text = $text;

/*    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    
		// Match IM TRYING
    $regexDingbats = '/[\x{0000}-\x{FFFF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);*/
		
		// $clean_text = preg_replace("/[^A-Za-z0-9 # \n]/", '', $text);		
		// $clean_text = preg_replace("/[^A-Za-z0-9\d\w\D\W \n]/", '', $text);		
		// $clean_text = preg_replace("/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]/", '', $text);		
		$clean_text =  preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);

		
    return $clean_text;
	}
	
	public static function count_combination($str) {
		$status_grab = false;
		$strlen = strlen( $str );
		$temp_count_combination = 0;
		for( $i = 0; $i <= $strlen; $i++ ) {
			$char = substr( $str, $i, 1 );
			if ($char=="{") {
				$temp = "";
				$status_grab = true;
			} 
			else if ($char=="}") {
				//proses hitung array dari $temp tersebut 
				$arr1 = explode("|",$temp);
				if ($temp_count_combination==0){
					$temp_count_combination = count($arr1);
				}
				else {
					$temp_count_combination = $temp_count_combination * count($arr1);
				}
				$status_grab = false;
			}
			else if ($status_grab) {
				$temp .= $char;
			}
		}
		return $temp_count_combination;
	}

	public function test(){
		echo $this->count_combination("{Culinary|Kuliner} of the day: {Hi|Hai|Hello|Helo|Alow|Allooo} {Just for|Untuk|Hanya untuk} yang {suka|suka makan|doyan} {Kremes Fried Chicken|Ayam Goreng Kremes}, {Check this out|ini ada} {Fried chicken|ayam goreng} {sedappp|nikmattt|enakkk|enak banget|tastyyy|ueeenak} to the bone, {wkwkwk|hahaha|hehehe}. Bisa {cek langsung|order|pesan|cobain} sendiri di @njaluksambal  - hati2 {nambah|ketagihan} yah ");
	}

	public function get_username(){
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
		
		/*$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Please start your account first";
      return $arr;
		}*/
							
		if (!$link->error_cred) {
			try {
				$i = new Instagram(false,false,[
					"storage"       => "mysql",
					"dbhost"       => Config::get('automation.DB_HOST'),
					"dbname"   => Config::get('automation.DB_DATABASE'),
					"dbusername"   => Config::get('automation.DB_USERNAME'),
					"dbpassword"   => Config::get('automation.DB_PASSWORD'),
				]);
				
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				// $i->setUser(strtolower($link->insta_username), $link->insta_password);
				$i->login(strtolower($link->insta_username), $link->insta_password, false,300);
				$usernames = $i->people->search(Request::input("search"))->getUsers();
				$arr_usernames = array();
				foreach ($usernames as $data) {
					// $arr_data["username"] = $data->getUsername();
					// $arr_data["id"] = $data->getUserId();
					// $arr_usernames[] = $arr_data;
					$arr_usernames[] = $data->getUsername();
				}
			}
			catch (Exception $e) {
				$arr["type"]="error";
				$arr["resultEmailData"] = $e->getMessage();
			}
			
			// $arr["arr_usernames"] = $arr_usernames;
			return json_encode($arr_usernames);
		}
		
		return $arr;
	}

	
}
