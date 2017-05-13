<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as req;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingMeta;
use Celebgramme\Models\LinkUserSetting;
use Celebgramme\Models\Post;
use Celebgramme\Models\Meta;
use Celebgramme\Models\Client;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Proxies;
use Celebgramme\Models\Category;
use Celebgramme\Models\SettingLog;
use Celebgramme\Models\TimeLog;
use Celebgramme\Models\Account;
use Celebgramme\Models\Message;

use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\ViewProxyUses;

use Celebgramme\Helpers\GlobalHelper;

use \InstagramAPI\Instagram;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, DB, Config;

class NewDashboardController extends Controller
{
  
	public function __construct()
	{
			include('simple_html_dom.php');
	}
	
	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function index(req $request){
    $user = Auth::user();
    $order = Order::where("order_status","=","pending")->where("user_id","=",$user->id)->where("image",'=','')->first();
		$status_server = Meta::where("meta_name","=","status_server")->first()->meta_value;
		
		$post = Post::where("type","=","home_page")->first();
		$content = $post->description;
		
		
    return view("new-dashboard.index")->with(array(
      'user'=>$user,
      'order'=>$order,
      'status_server'=>$status_server,
      'content'=>$content,
      ));
	}

	public function dashboard(){
    $user = Auth::user();
		
		
    return view("new-dashboard.dashboard")->with(array(
      'user'=>$user,
      ));
	}
}
