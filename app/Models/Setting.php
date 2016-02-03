<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;

use Celebgramme\Models\SettingMeta;

use Mail;

class Setting extends Model {

	protected $table = 'settings';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['activity_speed', 'media_source', 'media_age', 'media_type', 
    'min_likes_media', 'max_likes_media', 'dont_comment_su', 'follow_source', 'dont_follow_su', 'dont_follow_pu', 'unfollow_source', 'unfollow_wdfm', 'comments', 'tags', 'locations', 
    'insta_username', 'insta_password', 'insta_user_id', 'insta_access_token', 'last_user', 'start_time', 'running_time', 'user_id', 'tags_blacklist', 'usernames_blacklist', 
    'likes_counter', 'unfollows_counter', 'comments_counter', 'follows_counter', 'username', 'status', 'activity', 'usernames_whitelist', 'status_follow_unfollow', 'status_like', 'status_comment' ];
	protected function createSetting($arr)
	{
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "Nice <@owner>;{love|adore|really like|I love|enjoy|appreciate} {the way you|how you} {took|shot|had taken} {the|the actual|this particular|your|this} {picture|pic|image|photo|foto|snapshot} ^_^ <@owner>";
        $setting->tags = "websugih;jajankulinersurabaya;olshop;kuliner;latepost;startup;olshopmurah;yolo;travel;foodie";
        $setting->locations = "";
        $setting->activity = "follow";
        $setting->status_follow_unfollow = "on";
        $setting->status_like = "on";
        $setting->status_comment = "on";
        $setting->activity_speed = "normal";
        $setting->media_source = "hashtags";
        $setting->media_age = "1 hour";
        $setting->media_type = "any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
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
				
				//create meta, jumlah followers & following
				$followers_join = 0;
				$following_join = 0;
				$id = 0;
				$json_url = "https://api.instagram.com/v1/users/search?q=".$arr['insta_username']."&client_id=03eecaad3a204f51945da8ade3e22839";
				$json = @file_get_contents($json_url);
				if($json == TRUE) { 
					$links = json_decode($json);
					if (count($links->data)>0) {
						// $id = $links->data[0]->id;
						foreach($links->data as $link){
							if (strtoupper($link->username) == strtoupper($arr['insta_username'])){
								$id = $link->id;
							}
						}
						
						
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
				SettingMeta::createMeta("followers",$followers_join,$setting->id);
				SettingMeta::createMeta("following",$following_join,$setting->id);
				$setting->insta_user_id = $id;
				$setting->save();
        
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "Nice <@owner>;{love|adore|really like|I love|enjoy|appreciate} {the way you|how you} {took|shot|had taken} {the|the actual|this particular|your|this} {picture|pic|image|photo|foto|snapshot} ^_^ <@owner>";
        $setting->tags = "websugih;jajankulinersurabaya;olshop;kuliner;latepost;startup;olshopmurah;yolo;travel;foodie";
        $setting->locations = "";
        $setting->activity = "follow";
        $setting->status_follow_unfollow = "on";
        $setting->status_like = "on";
        $setting->status_comment = "on";
        $setting->activity_speed = "normal";
        $setting->media_source = "hashtags";
        $setting->media_age = "1 hour";
        $setting->media_type = "any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
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

        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$arr['user_id'];
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();
        return "";
	}

    //setting id temp
    protected function post_info_admin($setting_id,$type_message="[Celebgramme] Post Auto Manage") 
    {
        $setting_temp = Setting::find($setting_id);
        $setting_real = Setting::where("insta_user_id","=",$setting_temp->insta_user_id)->where("type","=","real")->first();
        $arr_temp = $setting_temp->toArray();
        $arr_real = $setting_real->toArray();
        unset($arr_temp['id']);unset($arr_temp['type']);unset($arr_temp['last_user']);unset($arr_temp['user_id']);
        unset($arr_real['id']);unset($arr_real['type']);unset($arr_real['last_user']);unset($arr_real['user_id']);
        $diff = array_diff_assoc($arr_temp,$arr_real);
        $act = "description: ";
        foreach ($diff as $key => $value) {
            $act .= $key." = ".strval($value)." ~ ";
        }
        $post = Post::where("setting_id","=",$setting_id)->first();
        if (is_null($post)){
          $post = new Post;
        }
        $post->setting_id = $setting_id;
        $post->description = $act;
        $post->type = "pending";
        $post->save();
				
				SettingMeta::createMeta("auto_unfollow","",$setting_temp->id);
				
				//send email to admin
				$emaildata = [
					"setting_temp" => $setting_temp,
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
				
				
        return $setting_temp;
    }

}
