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
				
				$i->setUser(strtolower($link->insta_username), $link->insta_password);
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);					
				}
				
				$i->login(false,300);
				if ( Request::input("type") == "approve" ) {
					$i->directThreadAction(Request::input("data_thread_id"), "approve");
					$chatAll = $i->directThread(Request::input("data_thread_id"));
					// $arr["chatAll"] = json_encode($chatAll);
					
					$arr["resultEmailData"] = view("new-dashboard.chat-all")->with(array(
																			'chatAll'=>$chatAll,
																			'setting_id'=>Request::input("setting_id"),
																			'thread_id'=>Request::input("data_thread_id"),
																			'username_user'=> $link->insta_username,
																			'data_pic'=> Request::input("data_pic"),
																		))->render();
					
				}
				else if ( Request::input("type") == "decline" ) {
					$i->directThreadAction(Request::input("data_thread_id"), "decline");
					$pendingInboxResponse = $i->getPendingInbox();

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
				
				$i->setUser(strtolower($link->insta_username), $link->insta_password);
				$proxy = Proxies::find($link->proxy_id);
				if (!is_null($proxy)) {
					$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
				}
				
				$i->login(false,300);
				
				$need_update = false;
				if (is_null($setting->last_update_inbox)) {
					$need_update = true;
				} else {
					$dt1 = Carbon::createFromFormat('Y-m-d H:i:s', $setting->last_update_inbox)->addMinutes(30);
					$dt2 = Carbon::now();
					if ( $dt2->lte($dt1) ) { 
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
							$inboxResponse = $i->getV2Inbox();
						} else {
							$inboxResponse = $i->getV2Inbox($end_cursor);
						}

						if (!is_null($inboxResponse->inbox->oldest_cursor)) {
							$end_cursor = $inboxResponse->inbox->oldest_cursor;
						} else {
							//klo null
							$has_next_page = false;
							$end_cursor = "";
						}
						
						//input array data
						if (count($inboxResponse->inbox->threads) > 0 ) {
							$counter_respond = 0;
							foreach ($inboxResponse->inbox->threads as $data_arr) {
								$date_message = substr($data_arr->items[0]->timestamp,0,10);
								$arr_data["date_message1"] = date("l, H:i:s", $date_message);
								$arr_data["date_message2"] = date("Y-m-d", $date_message);
								$text_message = $data_arr->items[0]->text;
								if (strlen($text_message)>=42) {
									$text_message = substr($text_message,0,115)." ...";
								}
								$arr_data["text_message"] = $text_message;
								//klo ga ada usernya di break
								if ( (is_null($data_arr->users)) || (empty($data_arr->users)) ) {
									continue;
								}
								$status_new_message = false;
								if ($data_arr->users[0]->pk == $data_arr->items[0]->user_id) {
									$status_new_message = true;
								}
								$arr_data["status_new_message"] = $status_new_message;
								$arr_data["user_id"] = $data_arr->items[0]->user_id;
								$arr_data["username"] = $data_arr->users[0]->username;
								$arr_data["profile_pic_url"] = $data_arr->users[0]->profile_pic_url;
								$arr_data["thread_id"] = $data_arr->thread_id;
								$arr_data["pk"] = $data_arr->users[0]->pk;
								
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
				if (Request::input("is_sort") == "1") {
					usort($arr_inbox, function($a, $b) {
						return $b['status_new_message'] - $a['status_new_message'];
					});
				}
				$total_data = count($arr_inbox);
				
				//buat pagination
				$page = Request::input('page'); // Get the current page or default to 1, this is what you miss!
				$perPage = 20;
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
        $pendingInboxResponse = $i->getPendingInbox();
        SettingMeta::createMeta("unseen_count",$pendingInboxResponse->inbox->unseen_count,Request::input("setting_id"));
				
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}
		
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
			$arr["message"]= "Direct Message memerlukan spin message, sebaiknya spin message anda mengandung lebih dari 250 kombinasi message";
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}
		
		//checking ga bole dalam hari yang sama, maximal 5
		$auto_responder = AutoResponderSetting::where("setting_id",Request::input("setting_id"))->count();
		if ( ($auto_responder >= 5) && (Request::input("id-auto-responder")=="new") ) {
			$arr["type"] = "error";
			$arr["message"] = "Auto responder tidak boleh lebih dari 5";
			return $arr;
		}
		$auto_responder = AutoResponderSetting::where("setting_id",Request::input("setting_id"))->get();
		foreach($auto_responder as $data ) {
			if ($data->num_of_day == Request::input("num_of_day")) {
				if (Request::input("id-auto-responder")<>$data->id) {   
					$arr["type"] = "error";
					$arr["message"] = "Hari ke - ".Request::input("num_of_day")." sudah ada pada database, silahkan edit data";
					return $arr;
				}
			}
		}
		
		if ((strpos(Request::input("message_responder"), '{') !== false) && (strpos(Request::input("message_responder"), '}')!==false)) {
		} else {
			$arr["message"]= "Auto Responder memerlukan spin message, sebaiknya spin message anda mengandung lebih dari 250 kombinasi message";
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
			
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
			$arr["type"] = "error";
			$arr["message"] = "Setting account belum distart";
      return $arr;
		}
			
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
		
		$setting = Setting::find(Request::input("setting_id"));
		if ($setting->status <> "started") {
      return redirect('dashboard')->with( 'error', 'Setting account belum distart');
		}
							
		$arr["message"] = "Proses delete berhasil dilakukan";
		$auto_responder = AutoResponderSetting::find(Request::input("id"))->delete();
		
		
		return $arr;
	}
	
	
}
