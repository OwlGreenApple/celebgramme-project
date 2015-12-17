<?php namespace Celebgramme\Models;

use Illuminate\Database\Eloquent\Model;

use Celebgramme\Models\LinkUserSetting;

class Setting extends Model {

	protected $table = 'settings';
  public $timestamps = false;
	protected function createSetting($arr)
	{
        $setting = new Setting;
        $setting->insta_username = $arr['insta_username'];
        $setting->insta_password = $arr['insta_password'];
        $setting->last_user = $arr['user_id'];
        //default data
        $setting->comments = "Nice,Pretty Awesome,Pretty Sweet,Aw Cool,Wow nice pictures";
        $setting->tags = "selfie,anime,kuliner,weekend,graduation";
        $setting->locations = "selfie,anime,kuliner,weekend,graduation";
        $setting->activity_speed = "normal";
        $setting->media_source = "tags";
        $setting->media_age = "1Hour";
        $setting->media_type = "Any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
        $setting->comment_su = true;
        $setting->follow_source = "media";
        $setting->follow_su = false;
        $setting->follow_pu = false;
        $setting->unfollow_source = "celebgramme";
        $setting->unfollow_wdfm = true;
        $setting->user_id = true;
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
        $setting->locations = "selfie,anime,kuliner,weekend,graduation";
        $setting->activity_speed = "normal";
        $setting->media_source = "tags";
        $setting->media_age = "1Hour";
        $setting->media_type = "Any";
        $setting->min_likes_media = "0";
        $setting->max_likes_media = "0";
        $setting->comment_su = true;
        $setting->follow_source = "media";
        $setting->follow_su = false;
        $setting->follow_pu = false;
        $setting->unfollow_source = "celebgramme";
        $setting->unfollow_wdfm = true;
        $setting->user_id = true;
        $setting->status = 'stopped';
        $setting->type = 'real';
        $setting->save();

        $linkUserSetting = new LinkUserSetting;
        $linkUserSetting->user_id=$arr['user_id'];
        $linkUserSetting->setting_id=$setting->id;
        $linkUserSetting->save();
        return "";
	}

}
