<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Veritrans\Veritrans;

use Illuminate\Http\Request;
use View, Input;

class CheckoutController extends Controller
{
	/**
	 *
	 * @return view
	 */
	public function checkout_finish(Request $request){
		return "finish";
	}

  
}
