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

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, Redirect;

class AdminController extends Controller
{
  
	public function user_list(){
		$users = User::all();
		foreach ($users as $user){
			echo "<a href='".url("check-super")."/".$user->id."'>".$user->email."</a> <br>";
		}
  }

	public function check_super($id){
		Auth::loginUsingId($id);
		return redirect("home");
	}
}
