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
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator, Carbon;

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
  
	public function checkout(){
		return view('check-out')->with(array());
	}

  	public function register_checkout() {
  		return view('reg-check-out')->with(array());
  	}

  	public function calculate_coupon() {
  		$dt = Carbon::now();
  		$coupon = Coupon::where("coupon_code","=",Request::input('couponcode'))
  					->where("valid_until",">=",$dt->toDateTimeString())->first();
  		if (!is_null($coupon)) {
  			$arr['show']=number_format($coupon->coupon_value,0,'','.');
  			$arr['real']= $coupon->coupon_value;
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
	      "package_id"=>Request::input("select-daily-like"),
	      "package_manage_id"=>Request::input("select-auto-manage"),
	      "coupon_code"=>Request::input("coupon-code"),
	      "payment_method"=>Request::input("payment-method"),
	      "total"=>$total,
	    );
	    
	    $request->session()->put('checkout_data', $arr);
	    return redirect("register-checkout");
    }
  
	
}
