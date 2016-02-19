<?php

namespace Celebgramme\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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


      })->everyFiveMinutes();

      $schedule->call(function () {


      })->twiceDaily(1,13);

      $schedule->call(function () {


      })->hourly();

      $schedule->call(function () {
				$user = User::find(99);
				$user->test=1;
				$user->save();

      })->everyMinute();


*/
    }
}
