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
use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\PackageUser;
use Celebgramme\Models\Package;
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\Post;
use Celebgramme\Models\Client;

use Celebgramme\Helpers\GeneralHelper;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, DB;

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
        //kurangin detik, buat auto manage
        $now = Carbon::now();
        $users = User::where("active_auto_manage",">",0)->get();
        foreach ($users as $user){
            $settings = Setting::where("type",'=','temp')
                        ->where('last_user','=',$user->id)
                        ->where('status','=',"started")
                        ->get();
            foreach($settings as $setting) {
                $runTime = Carbon::createFromFormat('Y-m-d H:i:s', $setting->running_time);
                $timevalue = $now->diffInSeconds($runTime);
                $user->active_auto_manage -= $timevalue;
                if ($user->active_auto_manage <= 0){
                    $user->active_auto_manage = 0;
                    $setting->status = 'stopped';
                        //post info ke admin
                        $post = Post::where('setting_id', '=', $setting->id)->first();
                        if (is_null($post)) {
                            $post = new Post;
                            $post->description = "description: status = stopped ";
                            $post->setting_id = $setting->id;
                        } else {
													if ($post->type == "pending") {
                            $post->description = $post->description." status = stopped ";
													} else {
                            $post->description = " status = stopped ";
													}
                        }
												$post->status_admin = false;
												$post->type = "pending";
                        $post->save();

												//send email to admin
												$type_message="[Celebgramme] Post Auto Manage";
												$type_message .= "IG ACCOUNT(TIME OUT): ".$setting->insta_username;
												$emaildata = [
													"setting_temp" => $setting,
													"post" => $post,
												];
												Mail::queue('emails.info-post-admin', $emaildata, function ($message) use ($type_message) {
													$message->from('no-reply@celebgramme.com', 'Celebgramme');
													$message->to("celebgramme.adm@gmail.com");
													$message->bcc(array(
														"celebgram@gmail.com",
														"michaelsugih@gmail.com",
														"it2.axiapro@gmail.com",
													));
													$message->subject($type_message);
												});
												
												
                }
                else{
                    $setting->running_time = $now->toDateTimeString();
                }
                $setting->save();
                $user->save();
            }
        }
	}


    public function notif_member(){
        $users = User::where("status_auto_manage","!=","")->get();
        foreach ($users as $user){
            if ( ($user->status_auto_manage=="member") && ($user->active_auto_manage<=432000) ) {
                $user->status_auto_manage="member-5days";
                $user->save();

                $emaildata = [
                    'user' => $user,
                ];
                Mail::queue('emails.notif-5days', $emaildata, function ($message) use ($user) {
                  $message->from('no-reply@celebgramme.com', 'Celebgramme');
                  $message->to($user->email);
                  $message->subject('[Celebgramme] 5 hari lagi nih, nggak berasa yah');
                });

            }
            if ( ($user->status_auto_manage=="member-5days") && ($user->active_auto_manage<=259200) ) {
                $user->status_auto_manage="member-3days";
                $user->save();

                $emaildata = [
                    'user' => $user,
                ];
                Mail::queue('emails.notif-3days', $emaildata, function ($message) use ($user) {
                  $message->from('no-reply@celebgramme.com', 'Celebgramme');
                  $message->to($user->email);
                  $message->subject('[Celebgramme] Welcome to celebgramme.com');
                });

            }
            if ( ($user->status_auto_manage=="member-3days") && ($user->active_auto_manage==0) ) {
                $user->status_auto_manage="member-expired";
                $user->save();

                $emaildata = [
                    'user' => $user,
                ];
                Mail::queue('emails.notif-expired', $emaildata, function ($message) use ($user) {
                  $message->from('no-reply@celebgramme.com', 'Celebgramme');
                  $message->to($user->email);
                  $message->subject('[Celebgramme] Hari ini service Celebgramme.com berakhir');
                });

            }
        }
    }
  
	
	/**
	 * Checking following & followers of user
	 *
	 * @return response
	 */
	public function auto_follow_unfollow(){
		$settings = Setting::where("type",'=','temp')
								->where('error_cred','=',0)
								//->where('status','=',"started")
								->get();
		foreach($settings as $setting) {
				$pp_url = "";
				$followers = 0;
				$following = 0;
				$id = 0; $found = false;
				$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=".Client::getClientId("cron-service");
				$json = @file_get_contents($json_url);
				if($json == TRUE) { 
					$links = json_decode($json);
					if (count($links->data)>0) {
						// $id = $links->data[0]->id;
						foreach($links->data as $link){
							if (strtoupper($link->username) == strtoupper($setting->insta_username)){
								$id = $link->id;
								$found = true;
								$pp_url = $link->profile_picture;
							}
						}
						
						$json_url ='https://api.instagram.com/v1/users/'.$id.'?client_id='.Client::getClientId("cron-service");
						$json = @file_get_contents($json_url);
						if($json == TRUE) { 
							$links = json_decode($json);
							if (count($links->data)>0) {
								$followers = $links->data->counts->followed_by;
								$following = $links->data->counts->follows;
							}
						}
					}
				}
				SettingMeta::createMeta("followers",$followers,$setting->id);
				SettingMeta::createMeta("following",$following,$setting->id);
				if ( (!$found) || !$this->checking_cred_instagram($setting->insta_username,$setting->insta_password) ) {
					$setting_temp = Setting::find($setting->id);
					$setting_temp->error_cred = true;
					$setting_temp->status = "stopped";
					$setting_temp->save();

					$setting_real = Setting::where('insta_user_id','=',$setting_temp->insta_user_id)->where('type','=','real')->first();
					$setting_real->error_cred = true;
					$setting_real->status = "stopped";
					$setting_real->save();

					$user = User::find($setting_temp->last_user);
					if (!is_null($user)) {
						$emaildata = [
								'user' => $user,
								'insta_username' => $setting_temp->insta_username,
						];
						Mail::queue('emails.error-cred', $emaildata, function ($message) use ($user) {
							$message->from('no-reply@celebgramme.com', 'Celebgramme');
							$message->to($user->email);
							$message->subject('[Celebgramme] Error Login Instagram Account');
						});
					}
				}
				//saveimage url to meta
				if ($pp_url<>"") {
					$extension = pathinfo($pp_url, PATHINFO_EXTENSION);
					// $filename = str_random(4)."-".str_slug($setting->insta_username).".".$extension;
					$filename = str_slug($setting->insta_username).".".$extension;
					
					//get file content
					$arrContextOptions=array(
							"ssl"=>array(
									"verify_peer"=>false,
									"verify_peer_name"=>false,
							),
					);  
					$file = file_get_contents($pp_url, false, stream_context_create($arrContextOptions));
					
					$save = file_put_contents("images/pp/".$filename, $file);
					if ($save) {
						SettingMeta::createMeta("photo_filename",$filename,$setting->id);
					}
				}
				
				if ( ($following >=7250 ) && ($setting->status == "started") ) {
					SettingMeta::createMeta("auto_unfollow","yes",$setting->id);

					$setting->activity = "unfollow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 7250 following IG Account)");
					
				}
				if ( ($following <=1000 ) && (SettingMeta::getMeta($setting->id,"auto_unfollow")=="yes" ) && ($setting->status == "started") ) {
					SettingMeta::createMeta("auto_unfollow","no",$setting->id);

					$setting->activity = "follow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 1000 following IG Account, from auto unfollow)");
				}
		}
	}
	
	public function update_insta_user_id(){
		$settings = Setting::all();
		foreach($settings as $setting) {
				$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=".Client::getClientId("cron-service");
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
		$datas = DB::connection('mysqlAffiliate')->select("select p.*,u.user_email,u.display_name from wp_af1posts p inner join wp_af1users u on u.id=p.post_author where post_title like 'CLB%' and post_content=''");		
		// dd($datas);
		// echo $datas[0]->ID;
		foreach ($datas as $data) {
			// echo $data->post_status."<br>";
			if ($data->post_status=="publish") {
				
				//kirim email create user
				$temp = array (
					"email" => $data->user_email,
				);
				$validator = Validator::make($temp, [
					'email' => 'required|email|max:255|unique:users',
				]);
				if ($validator->fails()){
					break;
				}

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
				
				$dt = Carbon::now();
				$order = new Order;
				$str = 'OCLB'.$dt->format('ymdHi');
				$order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
				$order->no_order = $order_number;
				$order->package_manage_id = 31;
				$package = Package::find(31);
				$order->total = $package->price;
				$order->user_id = $user->id;
				$order->save();

				$user->active_auto_manage = $package->active_days * 86400;
				$user->max_account = $package->max_account;
				$user->save();

				$emaildata = [
						'user' => $user,
						'password' => $string,
				];
				Mail::queue('emails.create-user', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@celebgramme.com', 'Celebgramme');
					$message->to($user->email);
					$message->subject('[Celebgramme] Welcome to celebgramme.com');
				});
				
				
				$affected = DB::connection('mysqlAffiliate')->update('update wp_af1posts set post_content = "registered" where id="'.$data->ID.'"');
			}
		}
	}


	public function checking_cred_instagram($username,$password){  
		$url = "https://www.instagram.com/accounts/login/?force_classic_login=&next=/oauth/authorize/".urlencode("client_id=".Client::getClientId("cron-service")."&redirect_uri=	http://localhost/redirect&response_type=token");
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
		
		$url = "https://www.instagram.com/accounts/login/?force_classic_login=&next=/oauth/authorize/".urlencode("client_id=".Client::getClientId("cron-service")."&redirect_uri=	http://localhost/redirect&response_type=token");
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

		$url = "https://www.instagram.com/accounts/login/?force_classic_login=&next=/oauth/authorize/".urlencode("client_id=".Client::getClientId("cron-service")."&redirect_uri=	http://localhost/redirect&response_type=token");
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
	
	
	public function reset_client_used(){
		$client = Client::update(['used'=>0,]);
	}		
}
