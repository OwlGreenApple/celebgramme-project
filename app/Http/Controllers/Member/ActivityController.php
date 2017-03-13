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

use Celebgramme\Veritrans\Veritrans;
use Celebgramme\Models\ViewProxyUses;

use Celebgramme\Helpers\GlobalHelper;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, DB;

class ActivityController extends Controller
{
  
	public function __construct()
	{
			include('simple_html_dom.php');
	}
	
  public function check_activity(){  
		//kirim data ke server automation untuk digenerate activity
    $arr["message"]= "Setting berhasil diupdate";
    $arr["type"]= "success";
    return $arr;
  }

	
}
