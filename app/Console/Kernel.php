<?php

namespace Celebgramme\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Carbon\Carbon;
use Celebgramme\Models\User;
use Celebgramme\Models\PackageUser;
use Celebgramme\Models\Setting;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Celebgramme\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(function () {

        $users = User::all();
        foreach ($users as $user){
            //if ($user->valid_until <> "0000-00-00 00:00:00") {

                $now = Carbon::now()->setTime(23, 59, 59);
                $date_until = Carbon::createFromFormat('Y-m-d H:i:s', $user->valid_until);
                if ($now->lte($date_until)) {
                    $packageUser = PackageUser::join("packages",'packages.id','=','packages_users.package_id')
                                    ->where("packages_users.user_id","=",$user->id)->orderBy('packages_users.created_at', 'desc')->first();
                    if (!is_null($packageUser)) {
                        $user->balance = $packageUser->daily_likes;
                    }

                    $user->save();
                } else 
                if ($date_until->lt($now)) {
                    user->balance = 0;
                    $user->save();
                }

            // }
        }

        
      })->everyMinute();

      

      $schedule->call(function () {

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


      })->everyFiveMinutes();



    }
}
