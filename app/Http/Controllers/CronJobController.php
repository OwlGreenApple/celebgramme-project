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
        $user = User::find(1);
        $user->test=123;
        $user->save();
		/*
        //kurangin detik, buat auto manage
        $now = Carbon::now();
        $users = User::where("active_auto_manage",">",0);
        foreach ($users as $user){
            $settings = Setting::where("type",'=','temp')
                        ->where('user_id','=',$user->id)
                        ->where('status','=',"started")
                        ->where('type','=',"temp")
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
                    $setting->running_time = $dt->toDateTimeString();
                }
                $setting->save();
                $user->save();
            }
        }
        */
	}
  
	
}
