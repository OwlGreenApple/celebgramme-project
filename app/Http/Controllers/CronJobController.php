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

				$dt = Carbon::createFromFormat('Y-m-d H:i:s', $user->valid_until);
				$packageUser = PackageUser::join("packages",'packages.id','=','packages_users.package_id')
								->where("packages_users.user_id","=",$user->id)->orderBy('packages_users.created_at', 'desc')->first();
				if (!is_null($packageUser)) {
			    	echo $packageUser->daily_likes."<br>";
			   	}
			   	
				// echo $dt->toDateString()."<br>";  
				// echo $user->email;
				// echo "<br>";
			}
		}
	}
  
  
	
}
