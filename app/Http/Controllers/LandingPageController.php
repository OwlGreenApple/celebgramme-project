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
use Celebgramme\Models\Setting;
use Celebgramme\Models\SettingHelper;
use Celebgramme\Models\Coupon;
use Celebgramme\Models\Package;
use Celebgramme\Models\Idaff;
use Celebgramme\Models\UserLog;
use Celebgramme\Models\Proxies;
use Celebgramme\Models\Meta;
use Celebgramme\Models\ViewProxyUses;
use Celebgramme\Models\Survey;

use \InstagramAPI\Instagram;

/* Celebpost model */
use Celebgramme\Models\Account;

use Celebgramme\Helpers\GeneralHelper;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, DB, Config;

class LandingPageController extends Controller
{
  
	public function survey(){
    return view('survey');
  }
  
	public function submit_survey(req $request){
    $survey= Survey::where("email",$request->email)->first();
    if (!is_null($survey)) {
      $arr["type"] = "error";
      $arr["message"] = "Email anda sudah terdaftar";
      return $arr;
    }
		
    if ($request->city == "") {
      $arr["type"] = "error";
      $arr["message"] = "Silahkan input kota anda tinggal dulu";
      return $arr;
    }
    if ($request->popular_olshop == "") {
      $arr["type"] = "error";
      $arr["message"] = "Silahkan input toko online IG terpopuler dulu";
      return $arr;
    }
    if ($request->selebgram == "") {
      $arr["type"] = "error";
      $arr["message"] = "Silahkan input selebgram terpopuler versi anda dulu";
      return $arr;
    }
    
    $count_survey = Survey::count();
    
    $no_undian = 100+$count_survey;
    
    $survey = Survey::create($request->all());
    $survey->no_undian = $no_undian;
    $survey->save();
    // dd($survey);
    $arr["type"] = "success";
    $arr["message"] = "Survey berhasil disubmit";
    $arr["noundian"] = $no_undian;
    
    $emaildata = [
      'no_undian' => $no_undian,
      'email' => $request->email,
      'nama' => $request->fullname,
    ];
    Mail::queue('emails.submit-survey', $emaildata, function ($message) use ($emaildata) {
      $message->from('no-reply@activfans.com', 'activfans');
      $message->to($emaildata["email"]);
      $message->subject('[activfans] Submit Survey');
    });
    
		return $arr;
  }
  
	public function testing(){
		exit;
		$dt = Carbon::now()->setTimezone('Asia/Jakarta')->subDays(8);
		$settings = Setting::join("setting_helpers","settings.id","=","setting_helpers.setting_id")
								->join("users","users.id","=","settings.last_user")
								->where("settings.type",'=','temp')
								->where("proxy_id","<>",0)
								->where("settings.running_time","<",$dt->toDateTimeString())
								->where(function ($query) {
									$query->where("settings.status","=","stopped")
												->orWhere("settings.status","=","deleted")
												->orWhere("users.active_auto_manage","=",0);
								})
								->get();
		dd($settings);
		
		
		$arr_proxys = array();
		
		$arr_proxys[] = [
			"proxy"=>"",
			"cred"=>"",
			"port"=>"",
			"no"=>"",
		];
		$arr_proxy = $arr_proxys[array_rand($arr_proxys)];


		if(App::environment() == "local"){
			$cookiefile = base_path().'/../general/ig-cookies/cookies-celebpost-'.$arr_proxy["no"].'.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/cookies-celebpost-'.$arr_proxy["no"].'.txt';
		}
		if (file_exists($cookiefile)) {
			unlink($cookiefile);
		}

		
		// if(is_null($cursor)){
			$url = "https://www.instagram.com/explore/tags/kids/?__a=1";
		// } else {
			// $url = "https://www.instagram.com/explore/tags/kids/?__a=1&max_id=J0HWVsMpQAAAF0HWVsMdwAAAFiYA";
		// }
		
		$c = curl_init();
			curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"]);
			curl_setopt($c, CURLOPT_PROXYPORT, $arr_proxy["port"]);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $arr_proxy["cred"]);
			curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_REFERER, $url);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
			curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
			$page = curl_exec($c);
			curl_close($c);

		$arr_res = json_decode($page,true);
		// $arr_res1 = json_decode($arr_res["tag"]);
		// var_dump($arr_res);
		// var_dump($arr_res["tag"]["media"]);
		var_dump($arr_res["tag"]["media"]);

	}
	
	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function package(){
		return view('package')->with(array());
	}
  
	public function checkout($id=""){
		$packages = Package::where("package_group","=","auto-manage")->where("affiliate","=",0)->orderBy('price', 'asc')->get();
		return view('check-out')->with(array(
			'packages'=>$packages,		
			'id'=>$id,		
		));
	}

	public function register_checkout() {
		return view('reg-check-out')->with(array());
	}

	public function calculate_coupon() {
		$valid = false;
		$dt = Carbon::now();
		$coupon = Coupon::where("coupon_code","=",Request::input('couponcode'))
					->where("valid_until",">=",$dt->toDateString())->first();
		if (!is_null($coupon)) {
			if ($coupon->user_id == 0 ) {
				if ($coupon->package_id == 0 ) {
					$valid = true;
				} else {
					if ($coupon->package_id==Request::input('packageid')){
						$valid = true;
					}else {
						$valid = false;
					}
				}
			} else {
				if (Auth::check()) {
					$user = Auth::user();
					if ($user->id == $coupon->user_id) {
						$valid = true;
					} else {
						$valid = false;
					}
				} else {
					$valid = false;
				}
			}
		} else {
			$valid = false;
		}

		if ($valid) {
			if ($coupon->coupon_percent == 0 ) {
				$arr['show']=number_format($coupon->coupon_value,0,'','.');
				$arr['real']= $coupon->coupon_value;
			} else if ($coupon->coupon_value == 0 ) {
				$package = Package::find(Request::input('packageid'));
				$val = floor ( $coupon->coupon_percent / 100 * $package->price );
				$arr['show']=number_format($val,0,'','.');
				$arr['real']= $val;
			}
		} else {
			$arr['show']="0";
			$arr['real']= 0;
		}
		return $arr;
	}

	public function process_package(req $request) {
		$total = 0;
		$package = Package::find(Request::input("select-daily-like"));
		if (!is_null($package)) {
			$total += $package->price;
		}
		$package = Package::find(Request::input("select-auto-manage"));
		if (!is_null($package)) {
			$total += $package->price;
		}
		$dt = Carbon::now();
		$coupon = Coupon::where("coupon_code","=",Request::input('couponcode'))
					->where("valid_until",">=",$dt->toDateTimeString())->first();
		if (!is_null($coupon)) {
			$total -= $coupon->coupon_value;
			if ($total<0) { $total =0; }
		}

		$arr = array (
			"package_id"=>Request::input("select-auto-manage"),
			"package_manage_id"=>Request::input("select-auto-manage"),
			"coupon_code"=>Request::input("coupon-code"),
			"payment_method"=>Request::input("payment-method"),
			"total"=>$total,
		);
		
		$request->session()->put('checkout_data', $arr);
		return redirect("register-checkout");
	}

	public function forgot_password() {
    return view('auth.forgot-password');
	}
	
	public function auth_forgot() {
		$email = Request::input("username");
		$user = User::where('email','=',$email)->first();
		if (is_null($user)) {
			return redirect('forgot-password')->with(array('error'=>'1',));
		}
		if (App::environment() == 'local'){
			$url = 'https://localhost/celebgramme/public/redirect-auth/';
		}
		else if (App::environment() == 'production'){
			$url = url('redirect-auth');
		}
		$secret_data = [
			'email' => $email,
			'register_time' => Carbon::now()->toDateTimeString(),
		];
		$emaildata = [
			'url' => $url.'/'.Crypt::encrypt(json_encode($secret_data)),
		];
		Mail::queue('emails.forgot-password', $emaildata, function ($message) use ($email) {
			$message->from('no-reply@activfans.com', 'Activfans');
			$message->to($email);
			$message->subject('[activfans] Email Forgot & RESET Password');
		});
		return redirect('forgot-password')->with(array('success'=>'1',));
	}
	
	public function redirect_auth(req $request,$cryptedcode)
	{
		try {
			$decryptedcode = Crypt::decrypt($cryptedcode);
			$data = json_decode($decryptedcode);
			$user = User::where("email","=",$data->email)->first();
			if (!is_null($user)) {
				$request->session()->put('email', $data->email);

        return view('auth.new-password');
				
			} else{
				return redirect("http://activfans.com/error-page/");
			}
		} catch (DecryptException $e) {
			return redirect("http://activfans.com/error-page/");
		}
	}	
	
	public function change_password(req $request){
		$email = $request->session()->get('email');
		$user = User::where("email",'=',$email)->first();
		$user->password = Request::input("password");
		$user->save();
		return redirect('login')->with(array("success"=>"Password berhasil diganti"));
	}

	public function post_back_idaff(){	
		$idaff = Idaff::where("invoice","=",Input::get("invoice"))->first();
		if (is_null($idaff)){
			$idaff = new Idaff;
			$idaff->trans_id = Input::get("transid");
			$idaff->invoice = Input::get("invoice");
			$idaff->executed = 0;
		} else {
			$idaff = Idaff::where("invoice","=",Input::get("invoice"))->first();
		}
		
    if($idaff->executed){
			exit;
    }
		$idaff->name = Input::get("cname");
		$idaff->email = Input::get("cemail");
		$idaff->phone = Input::get("cmphone");
		$idaff->status = Input::get("status");
		$idaff->grand_total = Input::get("grand_total");
		$idaff->save();
		
		if ( (strtolower($idaff->status) == "success") && (!$idaff->executed) ) {
			$flag = false;
			$isi_form_kaos = false;
			$user = User::where("email","=",$idaff->email)->first();
			if (is_null($user)) {
				$flag = true;
				$karakter= 'abcdefghjklmnpqrstuvwxyz123456789';
				$string = '';
				for ($i = 0; $i < 8 ; $i++) {
					$pos = rand(0, strlen($karakter)-1);
					$string .= $karakter{$pos};
				}

				$user = new User;
				$user->email = $idaff->email;
				$user->password = $string;
				$user->fullname = $idaff->name;
				$user->type = "confirmed-email";
				$user->save();
			}
			
			$dt = Carbon::now()->setTimezone('Asia/Jakarta');
			$order = new Order;
			$str = 'OCLB'.$dt->format('ymdHi');
			$order_number = GeneralHelper::autoGenerateID($order, 'no_order', $str, 3, '0');
			$order->no_order = $order_number;
			$order->order_status = "cron dari affiliate";
			
			$package = null;
			if ( (intval(Input::get("grand_total")) <500000 ) && (intval(Input::get("grand_total")) >=495000 ) ) {
				$order->package_manage_id = 41;
				$package = Package::find(41);
			}
			else if ( (intval(Input::get("grand_total")) <600000 ) && (intval(Input::get("grand_total")) >=595000 ) ) {
				$order->package_manage_id = 43;
				$package = Package::find(43);
			}
			else if ( (intval(Input::get("grand_total")) <700000 ) && (intval(Input::get("grand_total")) >=695000 ) ) {
				$order->package_manage_id = 42;
				$package = Package::find(42);
			}
			
      if(is_null($package)){
				exit;
      }
			$order->total = $package->price;
			$order->user_id = $user->id;
			$order->save();
			
			OrderMeta::createMeta("logs","create order from affiliate",$order->id);
			
			if ($flag) {
				$user->active_auto_manage = $package->active_days * 86400;
				$user->max_account = $package->max_account;
				$user->save();
				
				$emaildata = [
						'user' => $user,
						'password' => $string,
						'isi_form_kaos' => $isi_form_kaos,
				];
				Mail::queue('emails.create-user', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					$message->subject('[Activfans] Welcome to activfans.com (Info Login & Password)');
				});
			
			} else {
				$t = $package->active_days * 86400;
				$days = floor($t / (60*60*24));
				$hours = floor(($t / (60*60)) % 24);
				$minutes = floor(($t / (60)) % 60);
				$seconds = floor($t  % 60);
				$time = $days."D ".$hours."H ".$minutes."M ".$seconds."S ";

				$user_log = new UserLog;
				$user_log->email = $user->email;
				$user_log->admin = "Adding time from cron";
				$user_log->description = "give time to member. ".$time;
				$user_log->created = $dt->toDateTimeString();
				$user_log->save();
				
				
				$user->active_auto_manage += $package->active_days * 86400;
				$user->save();
				
				
				$emaildata = [
						'user' => $user,
						'isi_form_kaos' => $isi_form_kaos,
				];
				Mail::queue('emails.adding-time-user', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@activfans.com', 'Activfans');
					$message->to($user->email);
					$message->subject('[Activfans] Congratulation Pembelian Sukses, & Kredit waktu sudah ditambahkan');
				});
				
			}
			
			$idaff->executed = 1;
			$idaff->save();
		}
		
		
		
	}
		
	/*
	* uda ga dipake lagi, karena cara curl berubah
	*/
	public function get_photo_hashtags($hashtags,$cursor=null){
    $user = Auth::user();

		$arr_proxys = array();
		
		// klo mau dipake ini harus diisi
		$arr_proxys[] = [
			"proxy"=>"",
			"cred"=>"",
			"port"=>"",
			"no"=>"1",
		];
		$arr_proxy = $arr_proxys[array_rand($arr_proxys)];


		if(App::environment() == "local"){
			$cookiefile = base_path().'/../general/ig-cookies/cookies-celebpost-'.$arr_proxy["no"].'.txt';
		} else{
			$cookiefile = base_path().'/../public_html/general/ig-cookies/cookies-celebpost-'.$arr_proxy["no"].'.txt';
		}
		if (file_exists($cookiefile)) {
			unlink($cookiefile);
		}

		
		//get token first
		$url = "https://www.instagram.com/accounts/login/?force_classic_login";
		// echo $url;exit;
		$c = curl_init();
			curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"]);
			curl_setopt($c, CURLOPT_PROXYPORT, $arr_proxy["port"]);
			curl_setopt($c, CURLOPT_PROXYUSERPWD, $arr_proxy["cred"]);
			curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_REFERER, $url);
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
			curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
			$page = curl_exec($c);
			curl_close($c);


		preg_match_all('/<input type="hidden" name="csrfmiddlewaretoken" value="([A-z0-9]{32})"\/>/', $page, $token);
		// dd($token);exit;
		// echo $arr_proxy["no"]."<br>";
		
		$csrftoken = $token[1][0];
		// echo $csrftoken;exit;
		
		
		if(is_null($cursor)){
			$query_string = "ig_hashtag(".$hashtags.") { media.first(9) {";
		} else {
			$query_string = "ig_hashtag(".$hashtags.") { media.after(".$cursor.", 9) {";
		}
		$fields = array(
						'format' => 'json',
						// 'q' => "ig_hashtag(".Request::input("inputHashtags").") { media.after(".Request::input("endCursor").", 12) {
						'q' => $query_string.
							"count,
							nodes {
								caption,
								code,
								comments {
									count
								},
								date,
								dimensions {
									height,
									width
								},
								display_src,
								id,
								is_video,
								likes {
									count
								},
								owner {
									id, 
									username,is_private,followed_by_viewer
								},
								thumbnail_src
							},
							page_info{
								end_cursor
							}
						}}",
						'ref' => 'tags::show',
		);
		// url-ify the data for the POST
		$field_string = urldecode(http_build_query($fields));
		$len = strlen($field_string);
		// echo $field_string."<br>".$len; 
		// exit;

		//set up header 
		$arr = array(
					'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
					'x-csrftoken:'.$csrftoken,
					'x-instagram-ajax:1',
					'x-requested-with:XMLHttpRequest',
					'content-type:application/x-www-form-urlencoded; charset=UTF-8',
					'content-length:'.strlen($field_string),
		);

		$url = "https://www.instagram.com/query/";
		$c = curl_init();

		curl_setopt($c,CURLOPT_HTTPHEADER, $arr );

		curl_setopt($c, CURLOPT_PROXY, $arr_proxy["proxy"]);
		curl_setopt($c, CURLOPT_PROXYPORT, $arr_proxy["port"]);
		curl_setopt($c, CURLOPT_PROXYUSERPWD, $arr_proxy["cred"]);
		curl_setopt($c, CURLOPT_PROXYTYPE, 'HTTP');
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_REFERER, $url);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $field_string);
		curl_setopt($c, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt($c, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($c, CURLOPT_POST, true);
		$page = curl_exec($c);
		curl_close($c);

		$arr_res = json_decode($page,true);
		// var_dump($arr_res);
		
		// var_dump(json_decode($page,true));exit;
		// print_r($arr_res["media"]["nodes"]); exit;
		$result = array();
		foreach ($arr_res["media"]["nodes"] as $data) {
			//scrape media
			$result[] = [
				"url"=>$data["display_src"],
				"code"=>$data["code"],
				"media_id"=>$data["id"],
				"caption"=>$data["caption"],
				"owner"=>$data["owner"]["username"],
				"likes_count"=>$data["likes"]["count"],
				"comments_count"=>$data["comments"]["count"],
			]; 
		}
		$media_count = number_format($arr_res["media"]["count"],0,"",".");
		$end_cursor = $arr_res["media"]["page_info"]["end_cursor"];
		
		return response()->json([
			'result'=>$result,
			'media_count'=>$media_count,
			'end_cursor'=>$end_cursor,
		]);				
		
	}
		
	public function fixing_error(){
		$settings = Setting::where("status","=","started")
								->where("type","=","temp")
								->get();
		foreach($settings as $setting) {
			$user = User::find($setting->last_user);
			if (!is_null($user)) {
				if ($user->created_at <= "2016/11/01 16:00:00") {
					$user->active_auto_manage += 172800;
					$user->save();
				}
			}
			
		}
	}

	/*
	* FUNCTION CUMAN JALAN DI PRODUCTION, KARENA VIEW DATABASE
	*/
	public function get_proxy_id($insta_username){	
		$arr["is_on_celebgramme"] = 0;
		$setting_id = 0;
		//check insta_username ada di celebgramme 
		$check = Setting::join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->where("type","=","temp")
							->where("insta_username","=",$insta_username)
							->first();
		if (!is_null($check)) {
			$arr["is_on_celebgramme"] = 1;
			$setting_id = $check->setting_id;
		} else {
			$arr["is_on_celebgramme"] = 0;
		}
		

		//check insta_username yang di celebgramme ada proxy ?
		$proxy_id = 0;
		$check = Setting::join("setting_helpers","setting_helpers.setting_id","=","settings.id")
							->where("type","=","temp")
							->where("proxy_id","<>",0)
							->where("insta_username","=",$insta_username)
							->first();
		if (!is_null($check)) {
			$proxy_id = $check->proxy_id;
		}
		else {
			//data id yang pake proxy di celebgramme
			$array_clb = array();
			$data_setting_helper = SettingHelper::select('proxy_id')
														 ->where('proxy_id','<>',0)->get();
			foreach($data_setting_helper as $data) {
				$array_clb[] = $data->proxy_id;
			}

			//data id yang pake proxy di celebpost
			$array_clp = array();
			$accounts = Account::select('proxy_id')
									->where('proxy_id','<>',0)->get();
			foreach($accounts as $data) {
				$array_clp[] = $data->proxy_id;
			}

			
			//carikan proxy baru, yang available 
			/*error migration $availableProxy = ViewProxyUses::select("id","proxy","cred","port","auth",DB::raw(									"sum(count_proxy) as countP"))
												->groupBy("id","proxy","cred","port","auth")
												->orderBy("countP","asc")
												->having('countP', '<', 1)
												->get();*/
			$availableProxy = Proxies::
											select('id')
											->whereNotIn('id',$array_clb)
											->whereNotIn('id',$array_clp)
											->get();
			$arrAvailableProxy = array();
			foreach($availableProxy as $data) {
				/*error migration $check_proxy = Proxies::find($data->id);
				if ($check_proxy->is_error == 0){
					$dataNew = array();
					$dataNew["id"] = $data->id;
					$arrAvailableProxy[] = $dataNew;	
				}*/
				$dataNew = array();
				$dataNew["id"] = $data->id;
				$arrAvailableProxy[] = $dataNew;	
			}
			if (count($arrAvailableProxy)>0) {
				$proxy_id = $arrAvailableProxy[array_rand($arrAvailableProxy)]["id"];
			} else {
				/*error migration $availableProxy = ViewProxyUses::select("id","proxy","cred","port","auth",DB::raw(									"sum(count_proxy) as countP"))
													->groupBy("id","proxy","cred","port","auth")
													->orderBy("countP","asc")
													->first();
				if (!is_null($availableProxy)) {
					$proxy_id = $availableProxy->id;
				}*/
			}

			$setting_helper = SettingHelper::where("setting_id","=",$setting_id)->first();
			if (!is_null($setting_helper)) {
				$setting_helper->proxy_id = $proxy_id;
				$setting_helper->save();
			}

		}
		
	
		$arr["proxy_id"] = $proxy_id;
	
		return response()->json($arr);
	}
	
	
	public function check_instagram_username($insta_username){	
		$setting_temp = Setting::where("insta_username","=",$insta_username)
										->where("type","=","temp")
										->first();
		$setting_temp->status = "stopped";
		$setting_temp->save();

		/*$setting_real = Setting::where('insta_user_id','=',$setting_temp->insta_user_id)->where('type','=','real')->first();
		if (!is_null($setting_real)) {
			$setting_real->status = "stopped cron";
			$setting_real->save();
		}*/

		$setting_helper = SettingHelper::where("setting_id","=",$setting_temp->id)->first();
		if (!is_null($setting_helper)) {
			$setting_helper->cookies = "error auto by cron";
			$setting_helper->save();
		}

		$user = User::find($setting_temp->last_user);
		if (!is_null($user)) {
			$emaildata = [
					'user' => $user,
					'insta_username' => $setting_temp->insta_username,
			];
			Mail::queue('emails.error-cred', $emaildata, function ($message) use ($user) {
				$message->from('no-reply@activfans.com', 'Activfans');
				$message->to($user->email);
				$message->bcc("celebgramme.dev@gmail.com");
				$message->subject('[Activfans] Error Instagram Account Username');
			});
		}
		
	}
	
	
	public function get_username_available($username){
		try {
			$error_message="";
			$i = new Instagram(false,false,[
				"storage"       => "mysql",
				"dbhost"       => Config::get('automation.DB_HOST'),
				"dbname"   => Config::get('automation.DB_DATABASE'),
				"dbusername"   => Config::get('automation.DB_USERNAME'),
				"dbpassword"   => Config::get('automation.DB_PASSWORD'),
			]);	
			
					// $i->setProxy('http://sugiarto:sugiarto12@196.18.172.66:57159');
					// JANGAN LUPA DILOGIN TERLEBIH DAHULU
					$i->setProxy('http://208.115.112.98:10865');
					
					
					$i->login("mayymayyaa", "qwerty12345", 300);
					$usernames = $i->people->search($username)->getUsers();

					return $usernames;

		}  	
		catch (\InstagramAPI\Exception\IncorrectPasswordException $e) {
			//klo error password
			$error_message = $e->getMessage();
		}
		catch (\InstagramAPI\Exception\AccountDisabledException $e) {
			//klo error password
			$error_message = $e->getMessage();
		}
		catch (\InstagramAPI\Exception\CheckpointRequiredException $e) {
			//klo error email / phone verification 
			$error_message = $e->getMessage();
		}
		catch (\InstagramAPI\Exception\InstagramException $e) {
			$is_error = true;
			// if ($e->hasResponse() && $e->getResponse()->isTwoFactorRequired()) {
				// echo "2 Factor perlu dioffkan";
			// } 
			// else {
					// all other login errors would get caught here...
				echo $e->getMessage();
			// }
		}	
		catch (NotFoundException $e) {
			// echo $e->getMessage();
			echo "asd";
		}					
		catch (Exception $e) {
			$error_message = $e->getMessage();
			if ($error_message == "InstagramAPI\Response\LoginResponse: The password you entered is incorrect. Please try again.") {
				$error_message = $e->getMessage();
			} 
			if ( ($error_message == "InstagramAPI\Response\LoginResponse: Challenge required.") || ( substr($error_message, 0, 18) == "challenge_required") || ($error_message == "InstagramAPI\Response\TimelineFeedResponse: Challenge required.") || ($error_message == "InstagramAPI\Response\LoginResponse: Sorry, there was a problem with your request.") ){
				$error_message = $e->getMessage();
			}
		}
		return $error_message;
	}

	
	
}
