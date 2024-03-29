<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Order;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Package;
use Celebgramme\Models\PackageAffiliate;
use Celebgramme\Models\Coupon;
use Celebgramme\Models\UserAffiliate;
use Celebgramme\Models\OrderAffiliate;
use Celebgramme\Models\OrderUserAffiliate;
use Celebgramme\Models\VeritransModel;
use Celebgramme\Veritrans\Veritrans;

use Illuminate\Http\Request;
use View, Input, Carbon;

class PaymentController extends Controller
{
	public function __construct(){   
		Veritrans::$serverKey = env('VERITRANS_SERVERKEY');
		Veritrans::$isProduction = env('VERITRANS_IS_PRODUCTION');
	}
  
	/* Veritrans */
	/**
	 * Get and Redirect to Veritrans-payment-page URL
	 *
	 * @return Redirect
	 */
  public function save_order_amelia($user,$total,$no_order,$paket_id,$type){
    $useraffiliate = UserAffiliate::where('user_id_celebgramme',$user->id)
                        ->first();

    if(!is_null($useraffiliate)){
      $owner = UserAffiliate::where('is_admin',$useraffiliate->owner_id)
              ->first();

      $order = new OrderAffiliate;
      $order->no_order = $no_order;
      $order->type = 'extend';
      $order->owner_id = $owner->is_admin;
      $order->total = $total;
      $order->tagihan = $order->total*$owner->komisi_new/100;
      $order->save();

      $orderuser = new OrderUserAffiliate;
      $orderuser->order_id = $order->id;
      $orderuser->user_id = $useraffiliate->id;

      if(Input::get("type") == "max-account"){
        $orderuser->paket_id = 0;
      } else {
        $paket = explode('999', $paket_id);  
        $orderuser->paket_id = $paket[1];
      }
      
      $orderuser->save();
    }
  }

	public function process(Request $request){
    set_time_limit(0);
    $user = Auth::user();
    
    //hitung total
		$total = 0;
		
		if (Input::get("type") == "daily-activity" ) {
      if(env('APP_PROJECT')=='Amelia'){
        $package = PackageAffiliate::find(Input::get("package-auto-manage"));  
        if (!is_null($package)) {
          $total += $package->harga;
        }
      } else {
        $package = Package::find(Input::get("package-auto-manage"));  
        if (!is_null($package)) {
          $total += $package->price;
        }
      }
		}
		else if (Input::get("type") == "max-account" ) {
			if (Input::get("maximum-account") == "3" ) {
				$total += 100000;
			}
			else if (Input::get("maximum-account") == "6" ) {
				$total += 200000;
			}
			else if (Input::get("maximum-account") == "9" ) {
				$total += 300000;
			}
		}
		/*$dt = Carbon::now();
		$coupon = Coupon::where("coupon_code","=",Input::get("coupon-code"))
					->where("valid_until",">=",$dt->toDateTimeString())->first();
		if (!is_null($coupon)) {
			$total -= $coupon->coupon_value;
			if ($total<0) { $total =0; }
		}*/

    //transfer bank
    if (Input::get("payment-method") == 1) {
      $paket_id = Input::get("package-auto-manage");
      $type = 'transfer_bank';
      $coupon_code = Input::get("coupon-code");

      if(env('APP_PROJECT')=='Amelia'){
        if(Input::get("type") == "max-account") { 
          $paket_id = 0;
        } else {
          $paket_id = '999'.Input::get("package-auto-manage");
        }
        $type = 'rico-extend';
        $coupon_code = 0;
      }

      $data = array (
        "type" => Input::get("type"),
        "maximum_account" => Input::get("maximum-account"),
        "order_type" => $type,
        "order_status" => "pending",
        "user_id" => $user->id,
        "order_total" => $total,
        "package_id" => Input::get("package-daily-likes"),
        "package_manage_id" => $paket_id,
        "coupon_code" => $coupon_code,
        "logs" => "EXISTING MEMBER",
      );
      
      $order = Order::createOrder($data,true);

      if(env('APP_PROJECT')=='Amelia'){
        $this->save_order_amelia($user,$order->total,$order->no_order,$paket_id,Input::get("type"));
      }

      return redirect("buy-more")->with("message","Order telah dibuat, silahkan melakukan pembayaran & konfirmasi order anda");
    }
    
    //veritrans
    if (Input::get("payment-method") == 2) {   
      
      // Validation passes
      $vt = new Veritrans;
      // Populate items
      $items = [];

      // package
      array_push($items, [
        'id' => '#Package',
        'price' => $total,
        'quantity' => 1,
        'name' => "Paket ".$package->package_name,
      ]);
      $totalPrice = $total;
      // Populate customer's billing address
      $billing_address = [
        'first_name' => $user->fullname,
        'last_name' => "",
        'phone' => $user->phone_number,
      ];

      // Populate customer's Info
      $customer_details = array(
        'first_name' => $user->fullname,
        'last_name' => "",
        'email' => $user->email,
        'billing_address' => $billing_address,
      );
        
      $checkout_data['unique_id'] = uniqid();
      $transaction_data = array(
        'payment_type' => 'vtweb', 
        'vtweb' => array(
            // 'enabled_payments' => ["credit_card"],
            'credit_card_3d_secure' => true
        ),
        'transaction_details'=> array(
          'order_id' => $checkout_data['unique_id'],
          'gross_amount' => $totalPrice
        ),
        'item_details' => $items,
        'customer_details' => $customer_details
      );
      try
      {
        $checkout_data["order_type"] = "VERITRANS";
        $checkout_data["order_status"] = "PENDING";
        $checkout_data["user_id"] = $user->id;
        $checkout_data["order_total"] = $totalPrice;
        $checkout_data["email"] = $user->email;
        $checkout_data["package_id"] = $package->id;
        $request->session()->put('checkout_data', $checkout_data);
        $vtweb_url = $vt->vtweb_charge($transaction_data);
        return redirect($vtweb_url);
      } 
      catch (Exception $e) 
      {   
        return $e->getMessage;
      }
    }
	}
	
	/**
	 * Proceed HTTP POST data from Veritrans after Payment Process
	 *
	 */
	public function veritransNotification(Request $request){
		$vt = new Veritrans;
		$json = $request->all();
    // dd($json);
		VeritransModel::setValue($json['order_id'], json_encode($json));
		// Not Important Code
		$transaction = $json['transaction_status'];
		$type = $json['payment_type'];
		$order_id = $json['order_id'];
		$fraud = $json['fraud_status'];
		if ($transaction == 'capture'){
			// For credit card transaction, we need to check whether transaction is challenge by FDS or not
			if ($type == 'credit_card'){
				if($fraud == 'challenge'){
					// TODO set payment status in merchant's database to 'Challenge by FDS'
					// TODO merchant should decide whether this transaction is authorized or not in MAP
					echo "Transaction order_id: " . $order_id ." is challenged by FDS";
				} 
				else {
					// TODO set payment status in merchant's database to 'Success'
					echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
				}
			}
		}
		else if ($transaction == 'settlement'){
			// TODO set payment status in merchant's database to 'Settlement'
			echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		} 
		else if($transaction == 'pending'){
			// TODO set payment status in merchant's database to 'Pending'
			echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		}
		else if ($transaction == 'deny'){
			// TODO set payment status in merchant's database to 'Denied'
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		}
		else if ($transaction == 'expire' || $transaction == 'expired'){
			// TODO set payment status in merchant's database to 'Denied'
			echo "Transaction using " . $type . " with order_id: " . $order_id . " is expired.";
		}
	}
	/**
	 * Check Veritrans notification result.
	 * If success then proceed user's checkout data.
	 *
	 * @return Redirect
	 */
	public function veritransFinish(Request $request){
		// Validation
		if (!$request->session()->has('checkout_data')){
			return redirect('a');
		}
		else{
			$checkout_data = $request->session()->get('checkout_data');
		}
		// Get checkout data here
		$checkout_data = $request->session()->get('checkout_data');
		$json = VeritransModel::getValue($checkout_data['unique_id']);
		if ($json != ''){
			$status = $json->transaction_status;
			if ($status == 'capture'){
				if ($json->payment_type == 'credit_card'){
					$fraud = $json->fraud_status;
					if ($fraud == 'challenge'){
						$checkout_data['payment_status'] = 'challenge';
					}
					else if ($fraud == 'accept'){
						$checkout_data['payment_status'] = 'success';
					}
				}
				else{
					$checkout_data['payment_status'] = 'success';
				}
			}
			else if ($status == 'settlement'){
				$checkout_data['payment_status'] = 'success';
			}
			else if ($status == 'pending'){
				$checkout_data['payment_status'] = 'pending';
			}
			// Proceed checkout_data! Creating Order record..
      		$checkout_data["ext"]="";
      		if ($checkout_data['payment_status'] == 'success'){
				$order = Order::createOrder($checkout_data,false);
			} else {
				$order = Order::createOrder($checkout_data,true);
			}
			if ($checkout_data['payment_status'] != 'challenge'){
				VeritransModel::setValue($checkout_data['unique_id'], null);
			}
			$checkout_data['order_id'] = $order->id;
			$checkout_data['order_number'] = $order->no_order;
			$checkout_data['shortcode'] = str_replace('OAXM', '', $checkout_data['order_number']);
			// Create invoice if payment_status is success
			if ($checkout_data['payment_status'] == 'success'){
        		$invoice = Invoice::successPayment($checkout_data);
	 		}
	        if (($checkout_data['payment_status'] == 'challenge')||($checkout_data['payment_status'] == 'pending')){
	        }
			$request->session()->put('checkout_data', $checkout_data);
      
			return redirect('checkout-finish');
		}
		else{
			// Do something when no JSON
			return redirect('b');
		}
	}
	/**
	 * If user cancel/fail from Veritrans payment page, then redirect back to checkout-Payment-Method page
	 *
	 * @return Redirect
	 */
	public function veritransFail(Request $request){
		$checkout_data = $request->session()->get('checkout_data');
		VeritransModel::setValue($checkout_data['unique_id'], null);
		return redirect('c');
	}

  
	/*DOKU*/
	public function process_doku(){
		return view("form-doku");
	}

	public function notification_doku(){
	}

	public function doku_page($action){
		if($action=="verify"){
			$veritrans = new VeritransModel;
			$veritrans->save();
			return "Continue";
		}
	}
	
	
}
