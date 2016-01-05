<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;

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
    'likes_counter', 'unfollows_counter', 'comments_counter', 'follows_counter', 'username', 'status' ];
	protected function createSetting($arr)
	{
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "Nice,Pretty Awesome,Pretty Sweet,Aw Cool,Wow nice pictures";
        $setting->tags = "selfie,anime,kuliner,weekend,graduation";
        $setting->locations = "";
        $setting->activity_speed = "normal";
        $setting->media_source = "tags";
        $setting->media_age = "1 hour";
        $setting->media_type = "any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
        $setting->dont_comment_su = true;
        $setting->follow_source = "media";
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
        
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "Nice,Pretty Awesome,Pretty Sweet,Aw Cool,Wow nice pictures";
        $setting->tags = "selfie,anime,kuliner,weekend,graduation";
        $setting->locations = "";
        $setting->activity_speed = "normal";
        $setting->media_source = "tags";
        $setting->media_age = "1 hour";
        $setting->media_type = "any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
        $setting->dont_comment_su = true;
        $setting->follow_source = "media";
        $setting->dont_follow_su = false;
        $setting->dont_follow_pu = false;
        $setting->unfollow_source = "celebgramme";
        $setting->unfollow_wdfm = true;
        $setting->user_id = $arr['user_id'];
        $setting->status = 'stopped';
        $setting->type = 'real';
        $setting->save();

        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$arr['user_id'];
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();
        return "";
	}

    //setting id temp
    protected function post_info_admin($setting_id) 
    {
        $setting_temp = Setting::find($setting_id);
        $setting_real = Setting::where("insta_username","=",$setting_temp->insta_username)->where("type","=","real")->first();
        $arr_temp = $setting_temp->toArray();
        $arr_real = $setting_real->toArray();
        unset($arr_temp['id']);unset($arr_temp['type']);
        unset($arr_real['id']);unset($arr_real['type']);
        $diff = array_diff_assoc($arr_temp,$arr_real);
        $act = "description: ";
        foreach ($diff as $key => $value) {
            $act .= $key." = ".strval($value)." | ";
        }
        $post = Post::where("setting_id","=",$setting_id)->first();
        if (is_null($post)){
          $post = new Post;
        }
        $post->setting_id = $setting_id;
        $post->description = $act;
        $post->type = "pending";
        $post->save();
        return $setting_temp;
    }

}
