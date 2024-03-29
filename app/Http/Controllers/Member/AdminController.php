<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\Package;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\Setting;
use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\UserLog;
use Celebgramme\Models\AdminLog;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, Redirect;

class AdminController extends Controller
{
  
	public function user_list(){
		$user = Auth::user();
		if ($user->type=="admin") {
			$users = User::all();
			foreach ($users as $user){
				if ($user->id==1267) {
					continue;
				}
				echo "<a href='".url("check-super")."/".$user->id."'>".$user->email."</a> <br>";
			}
		} else {
			return "NOT AUTHORIZED";
		}
  }

	public function check_super($id){
		$admin = Auth::user();
		if ($admin->type=="admin") {
			if ($id==1267) {
				return "";
			}
			$dt = Carbon::now();
			$user = User::find($id);
			/*$user_log = new UserLog;
			$user_log->email = $user->email;
			$user_log->admin = $admin->fullname;
			$user_log->description = "admin (".$admin->fullname.") using super admin to access (".$user->email.")";
			$user_log->created = $dt->toDateTimeString();
			$user_log->save();*/

      $adminlog = new AdminLog;
      $adminlog->user_id = Auth::user()->id;
      $adminlog->description = "admin (".$admin->fullname.") using super admin to access (".$user->email.")";
      $adminlog->save();
			
			Auth::loginUsingId($id);
			return redirect("home");
		} else {
			return "NOT AUTHORIZED";
		}
	}
}

