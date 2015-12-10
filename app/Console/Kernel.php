<?php

namespace Celebgramme\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Carbon\Carbon;
use Celebgramme\Models\User;
use Celebgramme\Models\PackageUser;


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

        
      })->hourly();
            
            /* Add another CRON JOB here */
            // ...
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
