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
use Celebgramme\Models\Coupon;
use Celebgramme\Models\Package;
use Celebgramme\Models\Idaff;
use Celebgramme\Models\UserLog;
use Celebgramme\Veritrans\Veritrans;

use Celebgramme\Helpers\GeneralHelper;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt;

class LandingPageController extends Controller
{
  
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
			$url = 'https://celebgramme.com/celebgramme/redirect-auth/';
		}
		$secret_data = [
			'email' => $email,
			'register_time' => Carbon::now()->toDateTimeString(),
		];
		$emaildata = [
			'url' => $url.Crypt::encrypt(json_encode($secret_data)),
		];
		Mail::queue('emails.forgot-password', $emaildata, function ($message) use ($email) {
			$message->from('no-reply@celebgramme.com', 'Celebgramme');
			$message->to($email);
			$message->subject('[Celebgramme] Email Forgot & RESET Password');
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
				return redirect("http://celebgramme.com/error-page/");
			}
		} catch (DecryptException $e) {
			return redirect("http://celebgramme.com/error-page/");
		}
	}	
	
	public function change_password(req $request)
	{
		$email = $request->session()->get('email');
		$user = User::where("email",'=',$email)->first();
		$user->password = Request::input("password");
		$user->save();
		return redirect('login')->with(array("success"=>"Password berhasil diganti"));
	}

	public function post_back_idaff(){	
		/*
		[INVOICE] => Transaction Fee
		[AMOUNT] => Transaction Amount
		[CNAME] => Customer Name
		[CEMAIL] => Customer Email
		[CMPHONE] => Customer Phone
		[STATUS] => Transaction Status, Verified=Pending Payment, SUCCESS=Payment Received
		[IPADDRESS] => Customer IP Address
		[GRAND_TOTAL] => Total of Transactions Fee, Product Amount - Discount + Shipping Fee
		[SHIPPING_FEE] => Shipping Fee	
		*/
		$idaff = Idaff::where("trans_id","=",Input::get("ID"))->first();
		if (is_null($idaff)){
			$idaff = new Idaff;
			$idaff->trans_id = Input::get("ID");
			$idaff->executed = 0;
		} else {
			$idaff = Idaff::where("trans_id","=",Input::get("ID"))->first();
		}
		
		$idaff->name = Input::get("CNAME");
		$idaff->email = Input::get("CEMAIL");
		$idaff->phone = Input::get("CMPHONE");
		$idaff->status = Input::get("STATUS");
		$idaff->grand_total = Input::get("GRAND_TOTAL");
		$idaff->save();
		
		if ( ($idaff->status == "SUCCESS") && (!$idaff->executed) ) {
			$flag = false;
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
			$order->package_manage_id = 34;
			$order->order_status = "cron dari affiliate";
			$package = Package::find(34);
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
				];
				Mail::queue('emails.create-user', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@celebgramme.com', 'Celebgramme');
					$message->to($user->email);
					$message->subject('[Celebgramme] Welcome to celebgramme.com (Info Login & Password)');
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
				];
				Mail::queue('emails.adding-time-user', $emaildata, function ($message) use ($user) {
					$message->from('no-reply@celebgramme.com', 'Celebgramme');
					$message->to($user->email);
					$message->subject('[Celebgramme] Congratulation Pembelian Sukses, & Kredit waktu sudah ditambahkan');
				});
				
			}
			
			$idaff->executed = 1;
			$idaff->save();
		}
		
		
		
	}
		
}
