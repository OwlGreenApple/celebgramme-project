<?php

namespace Celebgramme\Http\Controllers;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Http\Request as req;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Models\UserMeta;
use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\PackageUser;
use Celebgramme\Models\Package;
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingLog;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\SettingCounter;
use Celebgramme\Models\PostTargetLike;
use Celebgramme\Models\FailedJob;
use Celebgramme\Models\Post;
use Celebgramme\Models\Client;
use Celebgramme\Models\Coupon;
use Celebgramme\Models\Meta;
use Celebgramme\Models\UserLog;
use Celebgramme\Models\TimeLog;
use Celebgramme\Models\Affiliate;
use Celebgramme\Models\Proxies;
use Celebgramme\Models\ProxyLogin;
use Celebgramme\Models\OrderAffiliate;
use Celebgramme\Models\OrderUserAffiliate;
use Celebgramme\Models\UserAffiliate;

/* Celebpost model */
use Celebgramme\Models\Account;
use Celebgramme\Models\UserCelebpost;

use Celebgramme\Helpers\GeneralHelper;
use Celebgramme\Helpers\GlobalHelper;

use \InstagramAPI\Instagram;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, DB, Config, Exception;

class CronJobController extends Controller
{
  
	/**
	 * Generating Balance for users 
	 *
	 * @return response
	 */
	public function generate_balance(){
		$users = User::all();
		foreach ($users as $user){
			if ($user->valid_until <> "0000-00-00 00:00:00") {

				$now = Carbon::now()->setTime(23, 59, 59);
				$date_until = Carbon::createFromFormat('Y-m-d H:i:s', $user->valid_until);
				if ($date_until->lte($now)) {
					$packageUser = PackageUser::join("packages",'packages.id','=','packages_users.package_id')
									->where("packages_users.user_id","=",$user->id)->orderBy('packages_users.created_at', 'desc')->first();
					if (!is_null($packageUser)) {
				    	$user->balance = $packageUser->daily_likes;
				   	}

				   	$user->save();
				}

			}
		}
	}
  
	public function auto_manage(){
		$count_log = 0;
		
		//kurangin detik, buat auto manage
		// $users = User::/*where("active_auto_manage",">",0)->*/all();
		// foreach ($users as $user){
			$settings = Setting::
									select("settings.id","settings.last_user","settings.running_time")
									->join("users","users.id","=","settings.last_user")
									->where("settings.type",'=','temp')
									->where('settings.status','=',"started")
									->get();
			foreach($settings as $setting) {
				$now = Carbon::now()->setTimezone('Asia/Jakarta');
				$update_user = User::find($setting->last_user);
				$update_setting = Setting::find($setting->id);
				$count_log += 1;
				$runTime = Carbon::createFromFormat('Y-m-d H:i:s', $setting->running_time);
				$timevalue = $now->diffInSeconds($runTime);
				$update_user->active_auto_manage -= $timevalue;
				if ($update_user->active_auto_manage <= 0){
					$update_user->active_auto_manage = 0;
					$update_setting->status = 'stopped';
					
					/*$description = "description: source_update = cron(timeout) ~ status = stopped ~";

					//send email to admin
					$type_message="[Activfans] Post Auto Manage";
					$type_message .= "IG ACCOUNT(TIME OUT): ".$setting->insta_username;
					$emaildata = [
						"setting_temp" => $setting,
						"description" => $description,
					];
					Mail::queue('emails.info-post-admin', $emaildata, function ($message) use ($type_message) {
						$message->from('no-reply@activfans.com', 'Activfans');
						$message->to(array(
							"it.axiapro@gmail.com",
						));
						// $message->bcc(array(
							// "celebgramme.dev@gmail.com",
						// ));
						$message->subject($type_message);
					});*/
				}
				else{
						$update_setting->running_time = $now->toDateTimeString();
				}
				$update_setting->save();
				$update_user->save();
			}
		// }
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/auto-manage-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
				
	}

/*
* harus dibuat cron jalan 2x sehari
*/
  public function notif_member(){
		$now = Carbon::now();
		$dt_coupon_expired = Carbon::now()->addDays(Meta::getMeta("coupon_setting_days")+1)->toDateString();
		$count_log = 0;
		$users = User::where("active_auto_manage","<=",453600)
							->where("active_auto_manage",">",0)
							->where("link_affiliate","=","")
							->where("is_member_rico ","=",0)
							->get();
		foreach ($users as $user){
				if ( ($user->active_auto_manage>=367200) && ($user->active_auto_manage<=453600) && (UserMeta::getMeta($user->id,"email5days")<>"yes") ) {
					$count_log += 1;
					$temp = UserMeta::createMeta("email5days","yes",$user->id);
					$temp = UserMeta::createMeta("emailExpCoupon","",$user->id);

					$emaildata = [
							'user' => $user,
					];
					Mail::queue('emails.notif-5days', $emaildata, function ($message) use ($user) {
						$message->from('no-reply@activfans.com', 'Activfans');
						$message->to($user->email);
						// $message->bcc("celebgramme.dev@gmail.com");
						$message->subject('[Activfans] 5 hari lagi nih, nggak berasa yah');
					});
				}
				if ( ($user->active_auto_manage>=43200) && ($user->active_auto_manage<=129600) && (UserMeta::getMeta($user->id,"email1days")<>"yes") ) {
					$count_log += 1;
					$temp = UserMeta::createMeta("email1days","yes",$user->id);

					//coupon diberi saat last day. coupon expired setelah x hari, tergantung setting
					do {
						$karakter= 'abcdefghjklmnpqrstuvwxyz123456789';
						$string = '';
						for ($i = 0; $i < 5 ; $i++) {
							$pos = rand(0, strlen($karakter)-1);
							$string .= $karakter{$pos};
						}
						$coupon = Coupon::where("coupon_code","=",$string)->first();
					} while (!is_null($coupon));
					$coupon = new Coupon;
					$coupon->coupon_value = Meta::getMeta('coupon_setting_value');
					$coupon->coupon_percent = Meta::getMeta('coupon_setting_percentage');
					$coupon->package_id = Meta::getMeta("coupon_setting_package_id");
					$coupon->coupon_code = $string;
					$coupon->user_id = $user->id;
					$coupon->valid_until = $dt_coupon_expired;
					$coupon->save();

					$emaildata = [
						'user' => $user,
						'code_coupon' => $string,
						'days_coupon' => Meta::getMeta("coupon_setting_days"),
						'percent_coupon' => Meta::getMeta('coupon_setting_percentage'),
					];
					Mail::queue('emails.notif-expired', $emaildata, function ($message) use ($user) {
						$message->from('no-reply@activfans.com', 'Activfans');
						$message->to($user->email);
						// $message->bcc("celebgramme.dev@gmail.com");
						$message->subject('[Activfans] Hari ini service Activfans.com berakhir');
					});
				}
				if ( ($user->active_auto_manage>0) && ($user->active_auto_manage<=50000) ) {
					$temp = UserMeta::createMeta("email1days","exp",$user->id);
					$temp = UserMeta::createMeta("email5days","exp",$user->id);
				}
		}
		
		$coupons = Coupon::where("user_id","!=",0)
								->where("valid_until","=",$now->toDateString())
								->get();
		foreach($coupons as $coupon){
			if (UserMeta::getMeta($user->id,"emailExpCoupon")<>"yes") {
				$count_log += 1;
				$temp = UserMeta::createMeta("emailExpCoupon","yes",$user->id);
				$user = User::find($coupon->user_id);
				$emaildata = [
					'user' => $user,
					'code_coupon' => $coupon->coupon_code,
				];
				Mail::queue('emails.notif-coupon-expired', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] Hari ini terakhir penggunaan coupon order anda');
				});
			}
		}
		
		$users = User::where("link_affiliate","<>","")->get();
		foreach ($users as $user){
			if ( ($user->active_auto_manage>=intval(UserMeta::getMeta($user->id,"start_waktu"))-172800) && ($user->active_auto_manage<=intval(UserMeta::getMeta($user->id,"start_waktu"))-86400) && (UserMeta::getMeta($user->id,"email-2-hari-affiliate")<>"yes") ) {
				$temp = UserMeta::createMeta("email-2-hari-affiliate","yes",$user->id);
				
				$emaildata = [
						'user' => $user,
				];
				Mail::queue('emails.free-trial-affiliate.awal-trial-2', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] Hi, sudah naik berapa followersnya ?');
				});
			}
			
			if ( ($user->active_auto_manage>=172800) && ($user->active_auto_manage<=259200) && (UserMeta::getMeta($user->id,"email-h-kurang-2-affiliate")<>"yes") ) {
				$temp = UserMeta::createMeta("email-h-kurang-2-affiliate","yes",$user->id);
				
				$emaildata = [
						'user' => $user,
				];
				Mail::queue('emails.free-trial-affiliate.h-kurang-2', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] Selamat, Anda mendapatkan Bonus 7 Video Rahasia OLShop Sukses Instagram');
				});
			}
			
			if ( ($user->active_auto_manage>=86400) && ($user->active_auto_manage<=172800) && (UserMeta::getMeta($user->id,"email-h-kurang-1-affiliate")<>"yes") ) {
				$temp = UserMeta::createMeta("email-h-kurang-1-affiliate","yes",$user->id);
				
				$emaildata = [
						'user' => $user,
				];
				Mail::queue('emails.free-trial-affiliate.h-kurang-1', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] Jualan di Instagram itu gampang yah');
				});
			}
			
			if ( ($user->active_auto_manage>0) && ($user->active_auto_manage<=86400) && (UserMeta::getMeta($user->id,"email-h-affiliate")<>"yes") ) {
				$temp = UserMeta::createMeta("email-h-affiliate","yes",$user->id);
				$dt = Carbon::now()->setTimezone('Asia/Jakarta')->addDays(2);
				$temp = UserMeta::createMeta("h-affiliate-end",$dt->toDateTimeString(),$user->id);
				
				$emaildata = [
						'user' => $user,
				];
				Mail::queue('emails.free-trial-affiliate.h', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] HARI ini Masa Trial Kakak HABIS');
				});
			}

			$dt = Carbon::now()->setTimezone('Asia/Jakarta');
			if ( ($user->active_auto_manage==0) && ( $dt >= UserMeta::getMeta($user->id,"h-affiliate-end") ) && (UserMeta::getMeta($user->id,"email-h-tambah-1-affiliate")<>"yes") ) {
				$temp = UserMeta::createMeta("email-h-tambah-1-affiliate","yes",$user->id);
				$nama_affiliate = UserMeta::getMeta($user->id,"nama affiliate");
				
				$emaildata = [
						'user' => $user,
						'nama_affiliate' => $nama_affiliate,
				];
				Mail::queue('emails.free-trial-affiliate.h-tambah-1', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					// $message->bcc("celebgramme.dev@gmail.com");
					$message->subject('[Activfans] Kesempatan nggak datang 2 kali');
				});
			}
			
		}
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
			echo $count_log;
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/notif-member-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
		
		
  }
  
	
	/**
	 * Count following & followers of user
	 *
	 * @return response
	 */
	public function count_instagram_data($insta_username= ""){
		include('simple_html_dom.php'); 
		$dt = Carbon::now()->setTimezone('Asia/Jakarta'); 
		
		$server_automation = "";
		/*if ($dt->hour == 18) {
			$server_automation = "AA1(automation-1)";
		}
		if ($dt->hour == 19) {
			$server_automation = "AA2(automation-2)";
		}
		if ($dt->hour == 20) {
			$server_automation = "AA3(automation-3)";
		}
		if ($dt->hour == 21) {
			$server_automation = "AA4(automation-4)";
		}
		if ($dt->hour == 22) {
			$server_automation = "AA5(automation-5)";
		}
		if ($dt->hour == 23) {
			$server_automation = "AA6(automation-6)";
		}
		if ($dt->hour == 0) {
			$server_automation = "AA7(automation-7)";
		}
		if ($dt->hour == 1) {
			$server_automation = "AA8(automation-8)";
		}
		if ($dt->hour == 2) {
			$server_automation = "AA9(automation-9)";
		}
		if ($dt->hour == 3) {
			$server_automation = "AA10(automation-10)";
		}
		if ($dt->hour == 4) {
			$server_automation = "AA12(automation-12)";
		}
		if ($dt->hour == 5) {
			$server_automation = "AA13(automation-13)";
		}
		if ($dt->hour == 6) {
			$server_automation = "AA14(automation-14)";
		}
		if ($dt->hour == 7) {
			$server_automation = "AA15(automation-15)";
		}*/
		$count_log = 0;
		
		// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
		// $txt = date("F j, Y, g:i a")." total rec : ".$count_log." ".$server_automation." IN";
		$txt = date("F j, Y, g:i a")." total rec : ".$count_log." ".$insta_username." ".$server_automation." IN";
		$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow-logs.txt', $txt.PHP_EOL , FILE_APPEND);
				
		if ($insta_username == "") {
			$settings = Setting::select("settings.*"/*,"setting_helpers.proxy_id"*/)
								->join("users","users.id","=","settings.last_user")
								// ->join("setting_helpers","settings.id","=","setting_helpers.setting_id")
								->where("settings.type",'=','temp')
								->where('settings.error_cred','=',0)
								->where('settings.status','=',"started")
								->where("users.active_auto_manage",">",0)
								// ->where("setting_helpers.server_automation","=",$server_automation)
								->get();
		} else {
			$settings = Setting::select("settings.*"/*,"setting_helpers.proxy_id"*/)
								// ->join("users","users.id","=","settings.last_user")
								// ->join("setting_helpers","settings.id","=","setting_helpers.setting_id")
								->where("settings.type",'=','temp')
								->where('settings.error_cred','=',0)
								// ->where("users.active_auto_manage",">",0)
								->where("insta_username","=",$insta_username)
								// ->where("setting_helpers.server_automation","=",$server_automation)
								->get();
		}
		foreach($settings as $setting) {
				$count_log += 1;
				$pp_url = "";
				$following = 0;
				$followers = 0;
				$id = 0; $found = false;
				
			$update_setting = Setting::find($setting->id);
				

				$user = User::find($setting->last_user);
				if (is_null($user)) {
					continue;
				}
				if ( ($user->test==0) || ($user->test==2) ) {
					// $ig_data = Setting::get_ig_data($setting->insta_username,$setting->id);
					$ig_data = Setting::get_ig_data($setting->insta_username);
					$found = $ig_data["found"];
					$id = $ig_data["id"];
					$pp_url = $ig_data["pp_url"];
					$following = $ig_data["following"];
					$followers = $ig_data["followers"];
				} 
				if ($found) {
					// SettingMeta::createMeta("followers",$followers,$setting->id);
					// SettingMeta::createMeta("following",$following,$setting->id);
					if(!is_null($update_setting)){
						$update_setting->num_of_following = $following;
						$update_setting->num_of_followers = $followers;
					}
					
				}
				else if (!$found)  {
					// pake cara API baru, klo dengan cara lama ada error proxy
					/*try {
							$i = new Instagram(false,false,[
								"storage"       => "mysql",
								"dbhost"       => Config::get('automation.DB_HOST'),
								"dbname"   => Config::get('automation.DB_DATABASE'),
								"dbusername"   => Config::get('automation.DB_USERNAME'),
								"dbpassword"   => Config::get('automation.DB_PASSWORD'),
							]);
							
							$proxy = Proxies::find($setting->proxy_id);
							if (!is_null($proxy)) {
								$i->setProxy("http://".$proxy->cred."@".$proxy->proxy.":".$proxy->port);
							}
							
							$i->login(strtolower($setting->insta_username), $setting->insta_password, 300);
							$self_info = $i->account->getCurrentUser()->getUser();
							$id = $self_info->getPk();
							$pp_url = $self_info->getProfilePicUrl();
							$following = $self_info->getFollowingCount();
							$followers = $self_info->getFollowerCount();
					} 
					catch (Exception $e) {
						if ( (strpos($e->getMessage(), 'Network: CURL error') !== false) || (strpos($e->getMessage(), 'No response from server') !== false) ) {
							continue;
						}
					}*/
				}
				
				//create logs following followers 
				$setting_log = new SettingLog;
				$setting_log->setting_id = $setting->id;
				if ($insta_username=="") {
					$setting_log->description = "Cron Daily";
					$setting_log->status = "Cron Daily following:".$following." followers:".$followers;
				} else {
					$setting_log->description = "Count by admin";
					$setting_log->status = "Count by admin following:".$following." followers:".$followers;
				}
				$setting_log->created = $dt->toDateTimeString();
				$setting_log->save();
				
				//klo insta_user_id == 0
				if ($setting->insta_user_id == "0"){
					$update_setting->insta_user_id = $id;
				}
				
				//saveimage url to meta
				if ($pp_url<>"") {
					$update_setting->photo_filename_url = $pp_url;
					/*try {
						$file_headers = get_headers($pp_url,1);
					}
					catch (Exception $e) {
						continue;
					}*/
          
					if(!$this->is_url_exist($pp_url)){
					// if(strpos($file_headers[0], '404') !== false){
					// echo "File Doesn't Exists!";
					} else {
						// echo "File Exists!";

						$extension = pathinfo($pp_url, PATHINFO_EXTENSION);
						// $filename = str_random(4)."-".str_slug($setting->insta_username).".".$extension;
						// $filename = $setting->id.".".$extension;
						$filename = $setting->id.".jpg";
						
						//get file content
						$arrContextOptions=array(
								"ssl"=>array(
										"verify_peer"=>true,
										"verify_peer_name"=>false,
								),
						);  
						$file = @file_get_contents($pp_url, false, stream_context_create($arrContextOptions));
						if($file == TRUE) {
							$save = file_put_contents("images/pp/".$filename, $file);
							if ($save) {
								// SettingMeta::createMeta("photo_filename",$filename,$setting->id);
								if(!is_null($update_setting)){
									$update_setting->photo_filename = $filename;
								}
								
							}
						}
						
						
					}
					
				}
				
				if ( ($following > $setting->max_follow ) && ($setting->activity == "follow") && (!$setting->status_auto) ) {
					// SettingMeta::createMeta("auto_unfollow","yes",$setting->id);
					if(!is_null($update_setting)){
						$update_setting->is_auto_unfollow = 1;

						$update_setting->activity = "unfollow";
						$update_setting->status_follow = "off";
						$update_setting->status_unfollow = "on";
					}
				}
				if ( ($setting->status_auto) && ($following > $setting->max_follow ) ) {
					// SettingMeta::createMeta("auto_unfollow","yes",$setting->id);
					if(!is_null($update_setting)){
						$update_setting->is_auto_unfollow = 1;
					
						$update_setting->status_follow_auto = 0;
						$update_setting->status_unfollow_auto = 1;
					}
				}

				// usleep(120000); 
				if(!is_null($update_setting)){
					$update_setting->save();
				}
		}
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			// $txt = date("F j, Y, g:i a")." total rec : ".$count_log." ".$server_automation." OUT";
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log." OUT";
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
		
		
	}
  
  public function is_url_exist($url){
    $ch = curl_init($url);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
    return $status;
  }
	
	public function update_insta_user_id(){
		$settings = Setting::all();
		foreach($settings as $setting) {
				$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
				$json = @file_get_contents($json_url);
				$id = 0;
				if($json == TRUE) { 
					$links = json_decode($json);
					if (count($links->data)>0) {
						// $id = $links->data[0]->id;
						foreach($links->data as $link){
							if (strtoupper($link->username) == strtoupper($setting->insta_username)){
								$id = $link->id;
							}
						}
					}
				}
				$setting->insta_user_id = $id;
				$setting->save();
				
		}		
	}
	
	/**
	 * replacing delimiter
	 *
	 * @return response
	 */
	public function replace_delimiter(){
		$settings = Setting::all();
		foreach($settings as $setting) {
			$setting->comments =  str_replace(",", ";", $setting->comments);
			$setting->hashtags =  str_replace(",", ";", $setting->hashtags);
			$setting->save();
		}
		$posts = Post::all();
		foreach($posts as $post) {
			$post->description = str_replace(",", ";", $post->description);
			$post->save();
		}
	}

	public function create_user_from_affiliate(){
		$count_log = 0; $new_user = 0; $adding_time = 0;
		$datas = DB::connection('mysqlAffiliate')->select("select p.*,u.user_email,u.display_name from wp_af1posts p inner join wp_af1users u on u.id=p.post_author where post_title like 'ACF%' and post_content='' and p.post_type='wuoysales' and post_status='publish'");		
		// dd($datas);
		// echo $datas[0]->ID;
		foreach ($datas as $data) {
			$count_log += 1;
			// echo $data->post_status."<br>";
			// if ($data->post_status=="publish") {
				
				//kirim email create user
				$temp = array (
					"email" => $data->user_email,
				);
				$validator = Validator::make($temp, [
					'email' => 'required|email|max:255',
					// 'email' => 'required|email|max:255|unique:users',
				]);
				if ($validator->fails()){
					continue;
				}

				$flag = false;
				$user = User::where("email","=",$data->user_email)->first();
				if (is_null($user)) {
					$flag = true;
					$karakter= 'abcdefghjklmnpqrstuvwxyz123456789';
					$string = '';
					for ($i = 0; $i < 8 ; $i++) {
						$pos = rand(0, strlen($karakter)-1);
						$string .= $karakter{$pos};
					}

					$user = new User;
					$user->email = $data->user_email;
					$user->password = $string;
					$user->fullname = $data->display_name;
					$user->type = "confirmed-email";
					$user->save();
				}
				
				$dt = Carbon::now();
				$order = new Order;
				$str = 'OCLB'.$dt->format('ymdHi');
				$order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
				$order->no_order = $order_number;
				// $order->package_manage_id = 31;
				// $order->package_manage_id = 34;
				$order->order_status = "cron dari affiliate";
				// $package = Package::find(31);
				$package = Package::find(45);
				$data_meta = DB::connection('mysqlAffiliate')->select("select meta_value from wp_af1postmeta where meta_key='price' and post_id = ".$data->ID);		
				$isi_form_kaos = false;
				if ( (intval($data_meta[0]->meta_value) <560000 ) && (intval($data_meta[0]->meta_value) >550000 ) ) {
					$package = Package::find(40);
					// $isi_form_kaos = true;
				} else if ( (intval($data_meta[0]->meta_value) >=455000 ) && (intval($data_meta[0]->meta_value) <457000 ) ) {
					$package = Package::find(34);
				} else if ( (intval($data_meta[0]->meta_value) >=459000 ) && (intval($data_meta[0]->meta_value) <461000 ) ) {
					$package = Package::find(39);
				} else if ( (intval($data_meta[0]->meta_value) >=595000 ) && (intval($data_meta[0]->meta_value) <600000 ) ) {
					$package = Package::find(45);
				} else if ( (intval($data_meta[0]->meta_value) >=695000 ) && (intval($data_meta[0]->meta_value) <700000 ) ) {
					$package = Package::find(46);
				} 
				/*
				$package = Package::select(DB::raw("ABS( price - CAST(".$data_meta[0]->meta_value." AS UNSIGNED) ) AS distance"))
				// $package = Package::select(DB::raw("ABS( price - 455000 ) AS distance"))
										->where("package_group","=","auto-manage")
										->orderBy('distance', 'asc')
										->first();
										*/
				// $order->total = $package->price;
				$order->total = intval($data_meta[0]->meta_value);
				$order->user_id = $user->id;
				$order->package_manage_id = $package->id;
				$order->save();
				
				OrderMeta::createMeta("logs","create order from affiliate",$order->id);

				if ($flag) {
					$new_user += 1;
					$user->active_auto_manage = $package->active_days * 86400;
					$user->max_account = 3;
					$user->save();
					
					$affected = DB::connection('mysqlAffiliate')->update('update wp_af1posts set post_content = "registered" where id="'.$data->ID.'"');
					
					$emaildata = [
							'user' => $user,
							'password' => $string,
							'isi_form_kaos' => $isi_form_kaos,
					];
					Mail::queue('emails.create-user', $emaildata, function ($message) use ($user) {
						$message->from('no-reply@activfans.com', 'Activfans');
						$message->to($user->email);
						$message->subject('[Activfans] Welcome to Activfans.com (Info Login & Password)');
					});
				
				} else {
          /*if($user->is_member_rico==1){
            $useraff = UserAffiliate::where('user_id_celebgramme',$user->id)->first();
            if(!is_null($useraff)){
              $orderaff = new OrderAffiliate;
              $orderaff->no_order = $order->no_order;
              $orderaff->type = 'extend';
              $orderaff->owner_id = 1;
              $orderaff->total = $order->total;

              $owner = UserAffiliate::where('is_admin',2)->first();

              $orderaff->tagihan = $order->total*$owner->komisi_new/100;
              $orderaff->save();

              $order_user = new OrderUserAffiliate;
              $order_user->order_id = $order->id;
              $order_user->user_id = $useraff->id;
              $order_user->paket_id = 0;
              $order->save();
            }
          }*/

					$t = $package->active_days * 86400;
					$days = floor($t / (60*60*24));
					$hours = floor(($t / (60*60)) % 24);
					$minutes = floor(($t / (60)) % 60);
					$seconds = floor($t  % 60);
					$time = $days."D ".$hours."H ".$minutes."M ".$seconds."S ";

					$user_log = new UserLog;
					$user_log->email = $user->email;
					$user_log->admin = "Adding time from cron";
					$user_log->description = "give time to member. ".$time;
					$user_log->created = $dt->toDateTimeString();
					$user_log->save();

					//buat log admin 
					$affiliate = Affiliate::where("link","=",$user->link_affiliate)->first();
					if (!is_null($affiliate)) {
						$affiliate->jumlah_user_beli += 1;
						$affiliate->save();
					}
					
					
					$adding_time += 1;
					$user->active_auto_manage += $package->active_days * 86400;
					if ($package->id == 44) {
						$user->max_account += $package->max_account;
					}
					
					//hapus affiliate link. Buy more jadi biasa, setelah pembelian pertama kali.
					$user->link_affiliate = "";
					
					$user->save();
					
					$affected = DB::connection('mysqlAffiliate')->update('update wp_af1posts set post_content = "registered" where id="'.$data->ID.'"');
					
					$emaildata = [
							'user' => $user,
							'isi_form_kaos' => $isi_form_kaos,
					];
					Mail::queue('emails.adding-time-user', $emaildata, function ($message) use ($user) {
						$message->from('no-reply@activfans.com', 'activfans');
						$message->to($user->email);
						$message->subject('[Activfans] Congratulation Pembelian Sukses, & Kredit waktu sudah ditambahkan');
					});
					
				}

				
				// $affected = DB::connection('mysqlAffiliate')->update('update wp_af1posts set post_content = "registered" where id="'.$data->ID.'"');
			// }
		}
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log."   - new user: ".$new_user."    - adding time: ".$adding_time;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/checking-cred-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
		
		
	}


	public function checking_cred_instagram($username,$password){  
		$url = "https://www.instagram.com/accounts/login/?force_classic_login";
		if(App::environment() == "local"){		
			$cookiefile = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$username.'-cookiess.txt';
		}
		$c = curl_init();
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

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_HTTPHEADER, array(
        'Accept-Language: en-US,en;q=0.8',
        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.89 Safari/537.36',
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
	
	
	public function reset_client_used(){
		$client = Client::update(['used'=>0,]);
	}		

	/*
	*
	* Cron Helper untuk daily automation
	*
	*/
	public function task_daily_automation_cron(){
		//reset IP pool login (for include in random)
		/*$proxy_logins = ProxyLogin::all();
		foreach ($proxy_logins as $proxy_login) {
			$proxy_login->is_error = 0;
			$proxy_login->save();
		}*/
		$affected = DB::table('proxy_logins')->update(array('is_error' => 0));
		
		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1);
		//delete failed job 
		$failed_job = FailedJob::
								// where("failed_at","<=",$dt->toDateTimeString())->
								truncate();
								
		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subDays(5);
		//delete post target like 
		$postTargetLike = PostTargetLike::
								where("created","<=",$dt->toDateTimeString())
								->where("status","=",0)
								->update(['status' => 2]);

		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subDays(5);
		// delete post target like 
		$postTargetLike = PostTargetLike::
								where("created","<=",$dt->toDateTimeString())
								->where("status","=",1)
								->delete();
		
		//daily time log untuk mencatat sisa waktu users
		$dt = Carbon::now()->setTimezone('Asia/Jakarta');
		//save ke log 
		$users = User::where("active_auto_manage",">",0)->get();
		foreach ($users as $user){
			$timeLog = new TimeLog;
			$timeLog->user_id = $user->id;
			$timeLog->time = $user->active_auto_manage;
			$timeLog->description = "daily log waktu users (cron)";
			$timeLog->created = $dt->toDateTimeString();
			$timeLog->save();
		}

		//klo IG account stop or deleted or waktu nya habis(TIMED out) lebih dari 8 hari, maka proxy akan dicabut, klo ada di celebpost maka table accounts di celebpost is_on_celebgramme di 0 kan
		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subDays(8);
		// $users = User::where("active_auto_manage","=",0)->get();
		// foreach ($users as $user){
		$settings = Setting::join("setting_helpers","settings.id","=","setting_helpers.setting_id")
								->join("users","users.id","=","settings.last_user")
								->where("settings.type",'=','temp')
								->where("proxy_id","<>",0)
								->where("settings.running_time","<",$dt->toDateTimeString())
								// ->where("settings.start_time","<>","0000-00-00 00:00:00")
								->where(function ($query) {
									$query->where("settings.status","=","stopped")
												->orWhere("settings.status","=","deleted")
												->orWhere("users.active_auto_manage","=",0);
								})
								->get();
		foreach($settings as $setting) {
			$update_setting_helper = SettingHelper::where("setting_id","=",$setting->setting_id)->first();
			
			$account = Account::where("proxy_id","=",$update_setting_helper->proxy_id)
									->where("username","=",$setting->insta_username)
									->first();
			if (!is_null($account)){
				$account->is_on_celebgramme = 0;
				$account->save();
			}
			
			$update_setting_helper->cookies = "";
			$update_setting_helper->proxy_id = 0;
			$update_setting_helper->save();
		}
		
		//pengecekan klo celebpost uda waktu habis atau, stopped maka proxy akan dicabut
		$user_celebpost = UserCelebpost::where("active_time",0)
											// ->orWhere("is_started",0)
											->get();
		foreach($user_celebpost as $data) {
			$accounts = Account::where("user_id",$data->id)
									->get();
			foreach($accounts as $account_celebpost){
				$account_celebpost->proxy_id = 0;
				$account_celebpost->save();
			}
		}
		$accounts = Account::where("is_started",0)
								->orWhereNull('is_started')
								->get();
		foreach($accounts as $account_celebpost){
			$account_celebpost->proxy_id = 0;
			$account_celebpost->save();
		}
		// }
		
		
		//buat kirim email ke order yang belum success 
		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subdays(2); 
		$dt1 = Carbon::now()->setTimezone('Asia/Jakarta')->subdays(7); 
		$orders = Order::where("is_remind_email",0)
							->where("order_status","pending")
							->where("created_at","<=",$dt->toDateTimeString())
							->where("created_at",">=",$dt1->toDateTimeString())
							->get();
		foreach($orders as $order){
			$user = User::find($order->user_id);
			$package = Package::find($order->package_manage_id);
			$shortcode = str_replace('OCLB', '', $order->no_order);
			$date_order = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at);
			//send email order
			$emaildata = [
					'order' => $order,
					'user' => $user,
					'package' => $package,
					'no_order' => $shortcode,
					'tanggal_order' => $date_order->format('d-m-Y'),
			];
			Mail::queue('emails.reminder', $emaildata, function ($message) use ($user,$shortcode) {
				$message->from('no-reply@activfans.com', 'Activfans');
				$message->to($user->email);
				$message->subject('[Activfans] Jangan lupa ada order yang belum terbayar');
			});
			
			$order->is_remind_email = 1;
			$order->save();
		}
		
		$setting_counter = null; $failed_job = null; $postTargetLike = null;
	}
	

	//function sudah ga dipake cuman dipake buat backup
	public function task_automation_like_backup(){
		$dt = Carbon::now()->setTimezone('Asia/Jakarta');
		$settings = Setting::select("settings.*","setting_helpers.number_likes","setting_helpers.identity")
								->join("setting_helpers","settings.id","=","setting_helpers.setting_id")
								->where("is_auto_get_likes","=",1)
								->where("settings.status","=","started")
								->get();
		foreach($settings as $setting) {
			//curl to IG account 3 latest post and get media id 
			//check if media id sudah di autolike atau belum
			//klo belum add ke post_target_like
			
			$ports[] = "10201";
			$ports[] = "10202";
			$ports[] = "10203";
			$port = $ports[array_rand($ports)];
			$cred = "sugiarto:sugihproxy250";
			$proxy = "45.79.212.85";//good proxy

			if(App::environment() == "local"){
				$cookiefile = base_path().'/../general/ig-cookies/'.$setting->insta_username.'-cookies-auto-like.txt';
			} else{
				$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$setting->insta_username.'-cookies-auto-like.txt';
			}
			
			$url = "https://www.instagram.com/".$setting->insta_username."/?__a=1";
			$c = curl_init();


			curl_setopt($c, CURLOPT_PROXY, $proxy);
			curl_setopt($c, CURLOPT_PROXYPORT, $port);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
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
			if (count($arr)>0) {
				$counter = 0;
				// echo $setting->insta_username."  ";
				if ($arr["user"]["is_private"]) {
					unlink($cookiefile);
					continue;
				}
				foreach ($arr["user"]["media"]["nodes"] as $data) {
					// echo $data["id"]."   ".$data["code"]."<br>";
					
					$postTargetLikeCheck = PostTargetLike::where("setting_id","=",$setting->id)
														->where("media_id","=",$data["id"])
														->first();
					if (is_null($postTargetLikeCheck)) {
						//create 
						$arr_setting_id_liker = array(); 
						$check_settings = Setting::select("settings.id","settings.status_auto","settings.status_like","settings.status_blacklist","settings.usernames_blacklist")
						->join("setting_helpers","settings.id","=","setting_helpers.setting_id")
						->where("settings.status","=","started")
						->where("setting_helpers.use_automation","=",1)
						->where("setting_helpers.cookies","=","success")
 					  ->where(function ($query) use ($setting) {
							$pieces = explode(";",$setting->identity);
							$query->where("setting_helpers.target","like","%none%");
							foreach($pieces as $piece){
								$query->orWhere("setting_helpers.target","like","%".$piece."%");
							}
						})
 					  ->where(function ($query) {
							$query->where("settings.status_auto","=",1);
							$query->orWhere("settings.status_like","=","on");
						})
						->get();
						foreach($check_settings as $check_setting) {
							$usernames_blacklist = array();
							if ($check_setting->status_blacklist) {
								$usernames_blacklist =  explode(";", $check_setting->usernames_blacklist);
							}
							if ( (!in_array(strtolower($setting->insta_username), $usernames_blacklist )) && ( ($check_setting->status_auto) || ($check_setting->status_like) ) ) {
								$arr_setting_id_liker[] = $check_setting->id;
							}
						}
						//random likers 
						shuffle($arr_setting_id_liker);

						//assign kan setting_id(ig_account) sebanyak x 
						for ( $i=0; $i<=$setting->number_likes - 1 ; $i++ ) {
							if ($i<count($arr_setting_id_liker)) {
								$postTargetLike = new PostTargetLike;
								$postTargetLike->media_id = $data["id"];
								$postTargetLike->owner_id = $data["owner"]["id"];
								$postTargetLike->code = $data["code"];
								$postTargetLike->setting_id = $setting->id;
								$postTargetLike->setting_id_liker = $arr_setting_id_liker[$i];
								$postTargetLike->status = 0;
								$postTargetLike->created = $dt->toDateTimeString();
								$postTargetLike->save();
							}
						}
					}
					
					$counter += 1;
					if ($counter==3) { break; }
				}
			} else {
				// echo "username not found";
			}
			
			
			unlink($cookiefile);


			
			
			
		}
	}

	
	public function fixing_error_cred(){
		$counter = 0 ;
		$settings = Setting::select("settings.id")
								->join("users","users.id","=","settings.last_user")
								->join("setting_helpers","setting_helpers.setting_id","=","settings.id")
								->where("setting_helpers.cookies",'=','error auto by cron')
								->where('setting_helpers.proxy_id','<>',0)
								->where("users.active_auto_manage",">",0)
								->get();
		foreach($settings as $setting) {
			$update_setting = Setting::find($setting->id);
			$update_setting->error_cred = 0;
			$update_setting->save();
			
			$counter += 1 ;
		}
		echo $counter;
		dd($settings);
	}

	public function check_all_proxy(){
		$proxies = Proxies::
								where("auth",1)
								->get();
		$logs = "";
		
		foreach($proxies as $data) {

			$port = $data->port;
			$cred = $data->cred;
			$proxy = $data->proxy;

			$cookiefile = base_path().'/../public_html/general/ig-cookies/check-proxies-cookiess.txt';
			if (file_exists($cookiefile)) {
				unlink($cookiefile);
			}
			$url = "https://www.instagram.com/rizky_redjo";
			$c = curl_init();


			curl_setopt($c, CURLOPT_PROXY, $proxy);
			curl_setopt($c, CURLOPT_PROXYPORT, $port);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $cred);
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
			$update_proxy = Proxies::find($data->id);
			if (count($arr)>0) {
				$update_proxy->is_error = 0;
				unlink($cookiefile);
			} else {
				// Kalo error proxy
				$logs .= $data->proxy.":".$port.":".$cred."<br>";
				$update_proxy->is_error = 1;
				
				//celebgramme ganti proxy trs di refresh supaya dapat cookies baru(klo perlu konfirmasi user diemail)..
				$setting_helpers = SettingHelper::where("proxy_id","=",$data->id)->get();
				foreach ($setting_helpers as $setting_helper){
					$proxy_id = GlobalHelper::getProxy();
					$update_setting_helper = SettingHelper::find($setting_helper->id);
					$update_setting_helper->proxy_id = $proxy_id;
					$update_setting_helper->is_refresh = 1;
					$update_setting_helper->cookies = "";
					$update_setting_helper->save();
					
					$setting = Setting::find($update_setting_helper->setting_id);
					if (!is_null($setting)) {
						$account = Account::where("username","=",$setting->insta_username)
												->first();
						if (!is_null($account)){
							$account->proxy_id = $proxy_id;
							$account->is_refresh = 1;
							$account->save();
						}
					}
				}
				
				//celebpost ganti proxy trs di refresh (klo perlu konfirmasi user diemail)
				$accounts = Account::where("proxy_id","=",$data->id)
										->where("is_on_celebgramme","=",0)
										->get();
				foreach ($accounts as $account){
					$proxy_id = GlobalHelper::getProxy();
					$update_account = Account::find($account->id);
					$update_account->proxy_id = $proxy_id;
					$update_account->is_refresh = 1;
					$update_account->save();
				}
			}
			$update_proxy->save();

			//0.12 detik
			usleep(120000);
		}
		echo $logs;
	}
}
