<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Client;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Proxies;

use Celebgramme\Models\SettingMeta;

use Mail, App;

class Setting extends Model {

	protected $table = 'settings';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['activity_speed', 'media_source', 'media_age', 'media_type', 
    'dont_comment_su', 'follow_source', 'dont_follow_su', 'dont_follow_pu', 'unfollow_source', 'unfollow_wdfm', 'comments', 'hashtags', 'locations', 
    'insta_username', 'insta_password', 'insta_user_id', 'insta_access_token', 'last_user', 'start_time', 'running_time', 'user_id', 'tags_blacklist', 'usernames_blacklist', 
    'username', 'status', 'activity', 'usernames_whitelist', 'status_follow_unfollow', 'status_like', 'status_comment', 'error_cred', "status_follow", "status_unfollow" ];
	protected function createSetting($arr)
	{
		$user = Auth::user();
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "{Nice|Wow|Good|Sippp|Mantappp|Keren|Mantap|Ok} {pic|img|photo|foto|profile|^_^|:)} <@owner>;{love|adore|really like|I love|enjoy|appreciate} {the way you|how you} {took|shot|had taken} {the|the actual|this particular|your|this} {picture|pic|image|photo|foto|snapshot} ^_^ <@owner>";
        $setting->hashtags = "websugih;jajankulinersurabaya;tacoelrico;celebgramme;olshop;kuliner;latepost;startup;olshopmurah;yolo;travel;foodie";
        $setting->locations = "";
        $setting->activity = "follow";
				$setting->status_follow = "on";
				$setting->status_unfollow = "off";
        $setting->status_follow_unfollow = "off";
        $setting->status_like = "off";
        $setting->status_comment = "off";
        $setting->activity_speed = "slow";
        $setting->media_source = "hashtags";
        $setting->media_age = "any";
        $setting->media_type = "any";
        $setting->dont_comment_su = true;
        $setting->follow_source = "hashtags";
        $setting->dont_follow_su = false;
        $setting->dont_follow_pu = false;
        $setting->unfollow_source = "celebgramme";
        $setting->unfollow_wdfm = true;
        $setting->user_id = $arr['user_id'];
        $setting->status = 'stopped';
        $setting->type = 'temp';
        $setting->save();

        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$arr['user_id'];
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();
				
				//Automation purpose
				$setting_helper = new SettingHelper;
				$setting_helper->setting_id = $setting->id;
				$setting_helper->use_automation = 1;
				$setting_helper->server_automation = "A1(automation-1)";
				$setting_helper->server_spiderman = "C1";
				$setting_helper->save();
				
				//create meta, jumlah followers & following
				$pp_url = "";
				$followers_join = 0;
				$following_join = 0;
				$id = 0;

				if ( ($user->test==0) || ($user->test==2) ){
					$ig_data = $this->get_ig_data($arr['insta_username']);
					$id = $ig_data["id"];
					$pp_url = $ig_data["pp_url"];
					$following_join = $ig_data["following"];
					$followers_join = $ig_data["followers"];
				} 
				
				SettingMeta::createMeta("followers_join",$followers_join,$setting->id);
				SettingMeta::createMeta("following_join",$following_join,$setting->id);
				SettingMeta::createMeta("followers",$followers_join,$setting->id);
				SettingMeta::createMeta("following",$following_join,$setting->id);
				SettingMeta::createMeta("fl_filename","-",$setting->id);
				$setting->insta_user_id = $id;
				$setting->save();
				
				//saveimage url to meta
				if ($pp_url<>"") {
					$extension = pathinfo($pp_url, PATHINFO_EXTENSION);
					// $filename = str_random(4)."-".str_slug($arr['insta_username']).".".$extension;
					$filename = str_slug($arr['insta_username']).".".$extension;
					
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
        
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "{Nice|Wow|Good|Sippp|Mantappp|Keren|Mantap|Ok} {pic|img|photo|foto|profile|^_^|:)} <@owner>;{love|adore|really like|I love|enjoy|appreciate} {the way you|how you} {took|shot|had taken} {the|the actual|this particular|your|this} {picture|pic|image|photo|foto|snapshot} ^_^ <@owner>";
        $setting->hashtags = "websugih;jajankulinersurabaya;tacoelrico;celebgramme;olshop;kuliner;latepost;startup;olshopmurah;yolo;travel;foodie";
        $setting->locations = "";
        $setting->activity = "follow";
				$setting->status_follow = "on";
				$setting->status_unfollow = "off";
        $setting->status_follow_unfollow = "off";
        $setting->status_like = "off";
        $setting->status_comment = "off";
        $setting->activity_speed = "slow";
        $setting->media_source = "hashtags";
        $setting->media_age = "any";
        $setting->media_type = "any";
        $setting->dont_comment_su = true;
        $setting->follow_source = "hashtags";
        $setting->dont_follow_su = false;
        $setting->dont_follow_pu = false;
        $setting->unfollow_source = "celebgramme";
        $setting->unfollow_wdfm = true;
        $setting->user_id = $arr['user_id'];
        $setting->status = 'stopped';
        $setting->type = 'real';
				$setting->insta_user_id = $id;
        $setting->save();

				/* needed ??
        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$arr['user_id'];
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();
				*/
        return "";
	}

    //setting id temp
    protected function post_info_admin($setting_id,$type_message="[Celebgramme] Post Auto Manage",$auto=false) 
    {
        $setting_temp = Setting::find($setting_id);
        $setting_real = Setting::where("insta_user_id","=",$setting_temp->insta_user_id)->where("type","=","real")->first();

				$arr_temp = $setting_temp->toArray();
				$arr_real = $setting_real->toArray();
				unset($arr_temp['id']);unset($arr_temp['type']);unset($arr_temp['last_user']);unset($arr_temp['user_id']);unset($arr_temp['start_time']);unset($arr_temp['running_time']);
				unset($arr_real['id']);unset($arr_real['type']);unset($arr_real['last_user']);unset($arr_real['user_id']);unset($arr_real['start_time']);unset($arr_real['running_time']);
				$diff = array_diff_assoc($arr_temp,$arr_real);
				$act = "description: ";
				if ($auto) {
					$act .= "source_update = cron ~ ";
				}
				foreach ($diff as $key => $value) {
					$act .= $key." = ".strval($value)." ~ ";
				}
				
				$post = Post::where("setting_id","=",$setting_id)->first();
				if (is_null($post)){
					$post = new Post;
				}
				$post->setting_id = $setting_id;
				$post->description = $act;
				$post->status_admin = false;
				
				//klo ada diff baru diupdate, sama statusnya started
				if ( ( ($setting_temp->status=="stopped") && ($setting_real->status=="stopped") )  ||  (count($diff)==0)  ) {
					$post->type = "success";
				} else {
					$post->type = "pending";
					// SettingMeta::createMeta("auto_unfollow","",$setting_temp->id);
					
					/*//send email to admin
					$type_message .= "IG ACCOUNT: ".$setting_temp->insta_username;
					$emaildata = [
						"setting_temp" => $setting_temp,
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
				*/
				}
				$post->save();
				
        return $setting_temp;
    }

    /*
		* get instagram data
		* return num of followers & following, id ig, pp url
		*/
    protected function get_ig_data($username,$setting_id = 0) 
		{
			$pp_url = "";
			$followers = 0;
			$following = 0;
			$id = 0; $found = false;

			$ports[] = "10201"; 
			$ports[] = "10202";
			$ports[] = "10203";
			$port = $ports[array_rand($ports)];
			$cred = "sugiarto:sugihproxy250";
			$proxy = "45.79.212.85";//good proxy
      $auth = true;
      
      //use own proxy if have
      if ($setting_id <> 0) {
        $setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
        if (!is_null($setting_helper)) {
          if ($setting_helper->proxy_id <> 0) {
            $full_proxy =  Proxies::find($setting_helper->proxy_id);
            if (!is_null($full_proxy)) {
              $port = $full_proxy->port;
              $cred = $full_proxy->cred;
              $proxy = $full_proxy->proxy;
              $auth = $full_proxy->auth;
            }
          }
        }
      }
      

			if(App::environment() == "local"){
				$cookiefile = base_path().'/../general/ig-cookies/'.$username.'-cookies-grab.txt';
			} else{
				$cookiefile = base_path().'/../public_html/general/ig-cookies/'.$username.'-cookies-grab.txt';
			}
			
			$url = "https://www.instagram.com/".$username."/?__a=1";
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
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
			curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
			$page = curl_exec($c);
			curl_close($c);
			
			$arr_json = json_decode($page,true);
			if (count($arr_json)>0) {
				$found = true;
				$id = $arr_json["user"]["id"];
				$pp_url = $arr_json["user"]["profile_pic_url"];
				$following = $arr_json["user"]["follows"]["count"];
				$followers = $arr_json["user"]["followed_by"]["count"];
				
			} else {
				// echo "username not found";
			}
			
			if (file_exists($cookiefile)) {
				unlink($cookiefile);
			}
			
			$arr = array(
				"id"=>$id,
				"pp_url"=>$pp_url,
				"following"=>$following,
				"followers"=>$followers,
				"found"=>$found,
			);
			
			return $arr;
			
		}
}
