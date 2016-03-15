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
		$count_log = 0;
		
        //kurangin detik, buat auto manage
        $now = Carbon::now();
        $users = User::where("active_auto_manage",">",0)->get();
        foreach ($users as $user){
            $settings = Setting::where("type",'=','temp')
                        ->where('last_user','=',$user->id)
                        ->where('status','=',"started")
                        ->get();
            foreach($settings as $setting) {
							$count_log += 1;
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
                            $post->description = "description: source_update = cron(timeout) ~ status = stopped ~";
                            $post->setting_id = $setting->id;
                        } else {
													if ($post->type == "pending") {
                            $post->description = $post->description." source_update = cron(timeout) ~ status = stopped ~";
													} else {
                            $post->description = "description: source_update = cron(timeout) ~ status = stopped ~";
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
														"design.axiapro@gmail.com",
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
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/auto-manage-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
				
	}


  public function notif_member(){
		$count_log = 0;
        $users = User::where("status_auto_manage","!=","")->get();
        foreach ($users as $user){
					$count_log += 1;
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
				
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/notif-member-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
				
				
  }
  
	
	/**
	 * Checking following & followers of user
	 *
	 * @return response
	 */
	public function auto_follow_unfollow(){
		include('simple_html_dom.php'); 
		$count_log = 0;
		$settings = Setting::where("type",'=','temp')
								->where('error_cred','=',0)
								//->where('status','=',"started")
								->get();
		foreach($settings as $setting) {
				$count_log += 1;
				$pp_url = "";
				$followers = 0;
				$following = 0;
				$id = 0; $found = false;

				$user = User::find($setting->last_user);
				if (is_null($user)) {
					continue;
				}
				if ($user->test==0){
					$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
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
							
							$json_url ='https://api.instagram.com/v1/users/'.$id.'?client_id=03eecaad3a204f51945da8ade3e22839';
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
				} else if ($user->test==1){
					$url = "http://websta.me/n/".$setting->insta_username;
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
						$found = true;
						$html = str_get_html($profbox[0]);
						if(($html->find('ul', 0))) {
							$temp = explode(" ", $html->find('ul')[0]->class );
							$temp1 = explode("-", $temp[1] );
							$id = $temp1[1];
						}
						if(($html->find('img', 0))) {
							$pp_url = $html->find('img')[0]->src;
						}
						if(($html->find('span[class="counts_followed_by"]', 0))) {
							$followers = str_replace(",","",$html->find('span[class="counts_followed_by"]')[0]->innertext);
						}
						if(($html->find('span[class="following"]', 0))) {
							$following = str_replace(",","",$html->find('span[class="following"]')[0]->innertext);
						}
					} else {
						$found = false;
					}
				}
				SettingMeta::createMeta("followers",$followers,$setting->id);
				SettingMeta::createMeta("following",$following,$setting->id);
				if (!$found) {
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
					
					$file_headers = get_headers($pp_url,1);					
					if(strpos($file_headers[0], '404') !== false){
					// echo "File Doesn't Exists!";
					} else {
						// echo "File Exists!";

						$extension = pathinfo($pp_url, PATHINFO_EXTENSION);
						// $filename = str_random(4)."-".str_slug($setting->insta_username).".".$extension;
						$filename = str_slug($setting->insta_username).".".$extension;
						
						//get file content
						$arrContextOptions=array(
								"ssl"=>array(
										"verify_peer"=>true,
										"verify_peer_name"=>false,
								),
						);  
						$file = file_get_contents($pp_url, false, stream_context_create($arrContextOptions));
						
						$save = file_put_contents("images/pp/".$filename, $file);
						if ($save) {
							SettingMeta::createMeta("photo_filename",$filename,$setting->id);
						}
						
						
					}
					
				}
				
				if ( ($following >=7250 ) && ($setting->status == "started") ) {
					SettingMeta::createMeta("auto_unfollow","yes",$setting->id);

					$setting->activity = "unfollow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 7250 following IG Account)",true);
					
				}
				if ( ($following <=1000 ) && (SettingMeta::getMeta($setting->id,"auto_unfollow")=="yes" ) && ($setting->status == "started") ) {
					SettingMeta::createMeta("auto_unfollow","no",$setting->id);

					$setting->activity = "follow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 1000 following IG Account, from auto unfollow)",true);
				}
		}
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
		
		
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
		$count_log = 0;
		$datas = DB::connection('mysqlAffiliate')->select("select p.*,u.user_email,u.display_name from wp_af1posts p inner join wp_af1users u on u.id=p.post_author where post_title like 'CLB%' and post_content='' and post_status='publish'");		
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
					'email' => 'required|email|max:255|unique:users',
				]);
				if ($validator->fails()){
					continue;
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
			// }
		}
		
		if(App::environment() == "local"){		
			// $file = base_path().'/../general/ig-cookies/'.$username.'-cookiess.txt';
		} else{
			// $file = base_path().'/../public_html/general/cron-job-logs/auto-follow-unfollow/logs.txt';
			$txt = date("F j, Y, g:i a")." total rec : ".$count_log;
			$myfile = file_put_contents(base_path().'/../public_html/general/cron-job-logs/checking-cred-logs.txt', $txt.PHP_EOL , FILE_APPEND);
		}
		
		
	}
}
