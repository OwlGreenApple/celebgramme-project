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

use View, Input, Mail, Request, App, Hash, Validator, Carbon;

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
                            $post->description = "stopped";
                        } else {
                            $post->description = $post->description." (stopped) ";
                        }
                        $post->save();
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
	 * Generating Data Followers and following now dari users
	 *
	 * @return response
	 */
	public function generate_data(){
		$settings = Setting::where("type",'=','temp')
								->get();
		foreach ($settings as $setting) {
			//create meta, jumlah followers & following
			$followers_join = 0;
			$following_join = 0;
			$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
			$json = @file_get_contents($json_url);
			if($json == TRUE) { 
				$links = json_decode($json);
				if (count($links->data)>0) {
					$id = $links->data[0]->id;

					$json_url ='https://api.instagram.com/v1/users/'.$id.'?client_id=03eecaad3a204f51945da8ade3e22839';
					$json = @file_get_contents($json_url);
					if($json == TRUE) { 
						$links = json_decode($json);
						if (count($links->data)>0) {
							$followers_join = $links->data->counts->followed_by;
							$following_join = $links->data->counts->follows;
						}
					}
				} 
			}
			SettingMeta::createMeta("followers_join",$followers_join,$setting->id);
			SettingMeta::createMeta("following_join",$following_join,$setting->id);
			
		}
	}


	/**
	 * Checking following & followers of user
	 *
	 * @return response
	 */
	public function auto_follow_unfollow(){
		$settings = Setting::where("type",'=','temp')
								->where('status','=',"started")
								->get();
		foreach($settings as $setting) {
				$followers = 0;
				$following = 0;
				$json_url = "https://api.instagram.com/v1/users/search?q=".$setting->insta_username."&client_id=03eecaad3a204f51945da8ade3e22839";
				$json = @file_get_contents($json_url);
				if($json == TRUE) { 
					$links = json_decode($json);
					if (count($links->data)>0) {
						$id = $links->data[0]->id;
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
				SettingMeta::createMeta("followers",$followers,$setting->id);
				SettingMeta::createMeta("following",$following,$setting->id);
				
				if ($following >=7250 ) {
					SettingMeta::createMeta("auto_unfollow","yes",$setting->id);

					$setting->activity = "unfollow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 7250 following IG Account)");
					
				}
				if ( ($following <=1000 ) && (SettingMeta::getMeta($setting->id,"auto_unfollow")=="yes" ) ) {
					SettingMeta::createMeta("auto_unfollow","no",$setting->id);

					$setting->activity = "follow";
					$setting->save();
					$setting_temp = Setting::post_info_admin($setting->id, "[Celebgramme] Post Auto Manage (warning 1000 following IG Account, from auto unfollow)");
				}
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
			$setting->tags =  str_replace(",", ";", $setting->tags);
			$setting->save();
		}
		$posts = Post::all();
		foreach($posts as $post) {
			$post->description = str_replace(",", ";", $post->description);
			$post->save();
		}
	}
}
