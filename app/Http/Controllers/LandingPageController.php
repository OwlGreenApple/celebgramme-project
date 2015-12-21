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
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator;

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

  	public function calculate_coupon() {
  		$coupon = Coupon::where("coupon_code","=",Request::input('couponcode'))->first();
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
	    $arr = array (
	      "package_id"=>Request::input("package"),
	      "payment_method"=>Request::input("payment-method"),
	    );
	    
	    $request->session()->put('checkout_data', $arr);
	    return redirect("register");
    }
  
	
}
