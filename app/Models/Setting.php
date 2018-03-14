<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Client;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Proxies;

use Celebgramme\Models\SettingMeta;

use \InstagramAPI\Instagram;

use Mail, App, Config;

class Setting extends Model {

	protected $table = 'settings';
	public $timestamps = false;

	protected $fillable = ['activity_speed', 'media_source', 'media_age', 'media_type', 
	'dont_comment_su', 'follow_source', 'dont_follow_su', 'dont_follow_pu', 'unfollow_source', 'unfollow_wdfm', 'comments', 'hashtags', 'locations', 
	'insta_username', 'insta_password', 'insta_user_id', 'insta_access_token', 'last_user', 'start_time', 'running_time', 'user_id', 'status_blacklist', 'usernames_blacklist', 
	'username', 'status', 'activity', 'status_whitelist','usernames_whitelist', 'status_follow_unfollow', 'status_like', 'status_comment', 'error_cred', "status_follow", "status_unfollow", "status_auto", "status_follow_auto", "status_unfollow_auto", "is_active", "is_like_followers", "percent_like_followers", "array_id_blacklist", "array_id_whitelist" ];

	protected function createSetting($arr)
	{
		$arr_proxy = $arr['arr_proxy'];
		$user = Auth::user();
		$setting = new Setting;
		$setting->insta_username = $arr['insta_username'];
		$setting->insta_password = $arr['insta_password'];
		$setting->last_user = $arr['user_id'];
		
		//default data
		// grab comment for emoji
		$comment = ""; $message = "";
		$setting_default_comment = Setting::find(7);
		if (!is_null($setting_default_comment)) {
			$comment = $setting_default_comment->comments;
			$message = $setting_default_comment->messages;
		}
		$setting->messages = $message;
		$setting->comments = $comment;
		$setting->hashtags = "websugih;jajankulinersurabaya;tacoelrico;jakarta;surabaya;digimaru;olshop;kuliner;latepost;startup;travel;foodie";
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
		$setting->dont_follow_pu = true;
		$setting->unfollow_source = "celebgramme";
		$setting->unfollow_wdfm = true;
		$setting->user_id = $arr['user_id'];
		$setting->status = 'stopped';
		$setting->type = 'temp';
		$setting->is_active = 0;
		$setting->method = "API";
		$setting->percent_like_followers = 25;
		$setting->save();
    
		$setting_id_temp = $setting->id;

		$linkUserSetting = new LinkUserSetting;
		$linkUserSetting->user_id=$arr['user_id'];
		$linkUserSetting->setting_id=$setting->id;
		$linkUserSetting->save();

		//Automation purpose
		$count_IG_account_server_AA1 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA1(automation-1)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA2 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA2(automation-2)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA3 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA3(automation-3)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA4 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA4(automation-4)%")
																	->where("settings.status","=","started")
																	->count();

		$count_IG_account_server_AA5 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA5(automation-5)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA6 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA6(automation-6)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA7 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA7(automation-7)%")
																	->where("settings.status","=","started")
																	->count();
		$count_IG_account_server_AA8 = SettingHelper::
																	join("settings","settings.id","=","setting_helpers.setting_id")
																	->where("cookies","=","success")
																	->where("server_automation","like","AA8(automation-8)%")
																	->where("settings.status","=","started")
																	->count();
		$setting_helper = new SettingHelper;
		$setting_helper->setting_id = $setting->id;
		$setting_helper->use_automation = 1;
		$setting_helper->proxy_id = $arr_proxy["proxy_id"]; //
		// $setting_helper->proxy_id = $arr_proxy["proxy_id"]; //
		if ( ($count_IG_account_server_AA3<=$count_IG_account_server_AA2) && ($count_IG_account_server_AA3<=$count_IG_account_server_AA1) && ($count_IG_account_server_AA3<=$count_IG_account_server_AA4) && ($count_IG_account_server_AA3<=$count_IG_account_server_AA7) && ($count_IG_account_server_AA3 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA3 <= $count_IG_account_server_AA5) && ($count_IG_account_server_AA3 <= $count_IG_account_server_AA8) ) {
			$setting_helper->server_automation = "AA3(automation-3)";
		} else if ( ($count_IG_account_server_AA2<=$count_IG_account_server_AA1) && ($count_IG_account_server_AA2<=$count_IG_account_server_AA4) && ($count_IG_account_server_AA2<=$count_IG_account_server_AA7) && ($count_IG_account_server_AA2 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA2 <= $count_IG_account_server_AA5) && ($count_IG_account_server_AA2 <= $count_IG_account_server_AA8) ) {
			$setting_helper->server_automation = "AA2(automation-2)";
		} else if ( ($count_IG_account_server_AA1<=$count_IG_account_server_AA4) && ($count_IG_account_server_AA1<=$count_IG_account_server_AA7) && ($count_IG_account_server_AA1 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA1 <= $count_IG_account_server_AA5) && ($count_IG_account_server_AA1 <= $count_IG_account_server_AA8) ) {
			$setting_helper->server_automation = "AA1(automation-1)";
		} else if ( ($count_IG_account_server_AA4<=$count_IG_account_server_AA7) && ($count_IG_account_server_AA4 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA4 <= $count_IG_account_server_AA5) && ($count_IG_account_server_AA4 <= $count_IG_account_server_AA8) ) {
			$setting_helper->server_automation = "AA4(automation-4)";
		} else if ( ($count_IG_account_server_AA8<=$count_IG_account_server_AA7) && ($count_IG_account_server_AA8 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA8 <= $count_IG_account_server_AA5) && ($count_IG_account_server_AA8 <= $count_IG_account_server_AA4) ) {
			$setting_helper->server_automation = "AA8(automation-8)";
		} else if ( ($count_IG_account_server_AA7 <= $count_IG_account_server_AA6) && ($count_IG_account_server_AA7 <= $count_IG_account_server_AA5) ) { 
			$setting_helper->server_automation = "AA7(automation-7)";
		} else if ($count_IG_account_server_AA5 <= $count_IG_account_server_AA6) {
			$setting_helper->server_automation = "AA5(automation-5)";
		} else {
			$setting_helper->server_automation = "AA6(automation-6)";
		}
		$setting_helper->is_need_relog_API = 1; 
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
			$filename = $setting->id.".".$extension;
			
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
		
		/*$setting = new Setting;
		$setting->insta_username = $arr['insta_username'];
		$setting->insta_password = $arr['insta_password'];
		$setting->last_user = $arr['user_id'];
		//default data
		$setting->comments = "{wow|waduh|walah|really|seriously|ga nyangka|masak sih} {kool|kueren|topdah|oklah|finee nice};{supper|vantastis|ketje|gaol} {bangetz|sangads|njedog|maximal} {voto|photo|foto|post|capturean} {hahaha|hehehe|hohoho|welewelewele} {kak|mas|bro|gan|sis} ;{masak|beneran|serius|ga boong|seriusan} {nih|nah|nich|this|barang ini} {wkwk|wkwkwk|akakaka|ahahaha};{oh|oo|OMG|oh wow|hah} {kak|mas|bro|gan|sis};{gila|heboh|ga masuk akal} {banget|barang|ini};{haha|wkwkwkkkk|GG|Lols} {Niceee|cool|pro|smile}";
		$setting->hashtags = "websugih;jajankulinersurabaya;tacoelrico;jakarta;surabaya;digimaru;olshop;kuliner;latepost;startup;travel;foodie";
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
		$setting->dont_follow_pu = true;
		$setting->unfollow_source = "celebgramme";
		$setting->unfollow_wdfm = true;
		$setting->user_id = $arr['user_id'];
		$setting->status = 'stopped';
		$setting->type = 'real';
		$setting->insta_user_id = $id;
		$setting->is_active = 0;
		$setting->save();*/
    
		return $setting_id_temp;
	}
	
	//setting id temp
	protected function post_info_admin($setting_id,$type_message="[Celebgramme] Post Auto Manage",$auto=false) 
	{
			$setting_temp = Setting::find($setting_id);
			//$setting_real = Setting::where("insta_user_id","=",$setting_temp->insta_user_id)->where("type","=","real")->first();//

			$arr_temp = $setting_temp->toArray();
			// $arr_real = $setting_real->toArray();//
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
			// if ( ( ($setting_temp->status=="stopped") && ($setting_real->status=="stopped") )  ||  (count($diff)==0)  ) {//
			if ( ($setting_temp->status=="stopped")   ||  (count($diff)==0)  ) {
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

		$auth = true;
		
				//new nata
				$arr_proxys[] = [
					"proxy"=>"103.56.206.11",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3128",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.11",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2017",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.11",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"1945",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.11",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2503",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.11",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3103",
				];			
				$arr_proxys[] = [
					"proxy"=>"103.56.206.12",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3128",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.12",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2017",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.12",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"1945",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.12",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2503",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.12",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3103",
				];			
				$arr_proxys[] = [
					"proxy"=>"103.56.206.13",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3128",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.13",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2017",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.13",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"1945",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.13",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2503",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.13",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3103",
				];			
				$arr_proxys[] = [
					"proxy"=>"103.56.206.54",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3128",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.54",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2017",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.54",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"1945",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.54",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2503",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.54",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3103",
				];			
				$arr_proxys[] = [
					"proxy"=>"103.56.206.55",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3128",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.55",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2017",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.55",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"1945",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.55",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"2503",
				];
				$arr_proxys[] = [
					"proxy"=>"103.56.206.55",
					"cred"=>"sugiarto:q1w2e3r4",
					"port"=>"3103",
				];			

		$arr_proxy = $arr_proxys[array_rand($arr_proxys)];

		//use own proxy if have
		if ($setting_id <> 0) {
			$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
			if (!is_null($setting_helper)) {
				if ($setting_helper->proxy_id <> 0) {
					$full_proxy =  Proxies::find($setting_helper->proxy_id);
					if (!is_null($full_proxy)) {
						$arr_proxy["port"] = $full_proxy->port;
						$arr_proxy["cred"] = $full_proxy->cred;
						$arr_proxy["proxy"] = $full_proxy->proxy;
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
			curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"]);
			curl_setopt($c, CURLOPT_PROXYPORT, $arr_proxy["port"]);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $arr_proxy["cred"]);
		} else if (!$auth) {
			curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"].":".$arr_proxy["port"]);
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

    /*
		* action klo error passowrd
		* return 
		* ONLY FOR AUTOMATION 
		*/
    protected function error_password($setting_id) 
		{
			$setting = Setting::find($setting_id);
			if (!is_null($setting)) {
				$setting->status = "stopped";
				$setting->error_cred = 1;
				$setting->save();
				// SettingMeta::createMeta("error_message_cred","*Data login error silahkan input kembali password anda",$setting_id);
				SettingMeta::createMeta("error_message_cred","*Error data login please input again your username password",$setting_id);
				
				//di 1 in supaya nanti di check cred lagi, abis edit password
				$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
				if (!is_null($setting_helper)) {
					$setting_helper->is_need_relog_API = 1;
					$setting_helper->save();
				}
			
				$link = LinkUserSetting::where("setting_id","=",$setting->id)->first();
				if (!is_null($link)) {
					$user = User::find($link->user_id);
					if (!is_null($user)) {
						$emaildata = [
							"user" => $user,
							"username" => $setting->insta_username,
							"appName" => Config::get('app.name'),
						];
						Mail::queue('emails.notif-error-3', $emaildata, function ($message) use ($user,$emaildata) {
							$message->from('no-reply@'.$emaildata["appName"].'.com', $emaildata["appName"]);
							$message->to($user->email);
							$message->bcc("celebgramme.dev@gmail.com");
							$message->subject("[ ".$emaildata["appName"]." ] Instagram Error Password.");
						});
					}
				}
			}
			return "";
		}
		
    /*
		* action klo Account Disabled
		* return 
		* ONLY FOR AUTOMATION 
		*/
    protected function error_account_disabled($setting_id) 
		{
			$setting = Setting::find($setting_id);
			if (!is_null($setting)) {
				$setting->status = "stopped";
				$setting->error_cred = 1;
				$setting->save();
				// SettingMeta::createMeta("error_message_cred","*Account DiDisable oleh instagram, Cek IG anda untuk merestore account",$setting_id);
				SettingMeta::createMeta("error_message_cred","*Your account has been disabled by Instagram, check your IG to restore it again",$setting_id);
				
				//di 1 in supaya nanti di check cred lagi, abis edit password
				$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
				if (!is_null($setting_helper)) {
					$setting_helper->is_need_relog_API = 1;
					$setting_helper->save();
				}
			
				$link = LinkUserSetting::where("setting_id","=",$setting->id)->first();
				if (!is_null($link)) {
					$user = User::find($link->user_id);
					if (!is_null($user)) {
						$emaildata = [
							"user" => $user,
							"username" => $setting->insta_username,
							"appName" => Config::get('app.name'),
						];
						Mail::queue('emails.notif-error-4', $emaildata, function ($message) use ($user,$emaildata) {
							$message->from('no-reply@'.$emaildata["appName"].'.com', $emaildata["appName"]);
							$message->to($user->email);
							$message->bcc("celebgramme.dev@gmail.com");
							$message->subject("[ ".$emaildata["appName"]." ] Check your Instagram account.");
						});
					}
				}
			}
			return "";
		}
		
    /*
		* action klo error perlu notifikasi
		* return 
		* ONLY FOR AUTOMATION 
		*/
    protected function error_notification($setting_id)
		{		
			$setting = Setting::find($setting_id);
			if (!is_null($setting)) {
				$setting->status = "stopped";
				// $setting->error_cred = 1;
				$setting->save();
				// SettingMeta::createMeta("error_message_cred","*Data login error  silahkan verifikasi account IG anda lewat HP / browser lalu input kembali password anda",$setting_id);
				SettingMeta::createMeta("error_message_cred","*Please verify your account IG from phone / web browser.",$setting_id);
				
				//di 1 in supaya nanti di check cred lagi, abis edit password
				$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
				if (!is_null($setting_helper)) {
					$setting_helper->is_need_relog_API = 1;
					$setting_helper->save();
				}
			
				$link = LinkUserSetting::where("setting_id","=",$setting->id)->first();
				if (!is_null($link)) {
					$user = User::find($link->user_id);
					if (!is_null($user)) {
						$emaildata = [
							"user" => $user,
							"username" => $setting->insta_username,
							"appName" => Config::get('app.name'),
						];
						Mail::queue('emails.notif-error-2', $emaildata, function ($message) use ($user,$emaildata) {
							$message->from('no-reply@'.$emaildata["appName"].'.com', $emaildata["appName"]);
							$message->to($user->email);
							$message->bcc("celebgramme.dev@gmail.com");
							$message->subject("[ ".$emaildata["appName"]." ] Please verify your Instagram account.");
						});
					}
				}
			}
			return "";
		}
		
  
}
