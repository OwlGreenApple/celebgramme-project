<?php

namespace Celebgramme\Http\Controllers\Auth;

use Celebgramme\Models\User;
use Celebgramme\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

use Input, Redirect, App, Hash, Mail, Crypt, Carbon;

use Celebgramme\Models\VeritransModel;
use Celebgramme\Models\Package;
use Celebgramme\Models\Order;
use Celebgramme\Veritrans\Veritrans;


class RegisterController extends Controller
{
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
    
		Veritrans::$serverKey = env('VERITRANS_SERVERKEY');
		Veritrans::$isProduction = false;
	}
  
	/**
	 * Menampilkan Halaman Register
	 *
	 * @return response
	 */
	public function getRegister(Request $request)
	{
		if ($request->session()->has('register_data')) {
			$request->session()->forget('register_data');
		}
		if (session('user_data')){
			$user_data = session('user_data');
		}
		else{
			$user_data = [
				'username' => '',
				'email' => '',
				'social_login' => false,
				'social_token' => '',
			];
		}
		// dd($user_data);
		return view('auth/register')->with([
			'user_data' => $user_data,
			'grecaptcha_key' => env('GOOGLE_RECAPTCHA_KEY'),
		]);
	}
	
	/**
	 * Memproses Data User yang mendaftar
	 *
	 * @return response
	 */
	public function postRegister(Request $request)
	{
    $validator  = User::validator($request->all());
    if (!$validator->fails()){
      $user = User::create($request->all());
      $dt = Carbon::now();
      $user->status_free_trial = 1;
      $user->valid_until = $dt->addDays(3)->toDateTimeString();
      $user->type = "not-confirmed";
      $user->save();

    } else {
      return "data tidak valid";
    }
    
    Auth::attempt(['email' => $request->email, 'password' => $request->password], true);
    //send email konfirmasi email
    $register_time = Carbon::now()->toDateTimeString();
    $request->session()->put('resend_email', $register_time);
    $verificationcode = Hash::make($user->email.$register_time);
    $user->verification_code = $verificationcode;
    $user->save();
    if (App::environment() == 'local'){
      $url = 'http://localhost/celebgramme/public/verifyemail';
    }
    else if (App::environment() == 'production'){
      $url = 'http://celebgramme.com/verifyemail/';
    }
    $secret_data = [
      'email' => $user->email,
      'register_time' => $register_time,
      'verification_code' => $verificationcode,
    ];
    $emaildata = [
      'url' => $url.Crypt::encrypt(json_encode($secret_data)),
    ];
    Mail::queue('emails.confirm-email', $emaildata, function ($message) use ($user) {
      $message->from('no-reply@celebgramme.com', 'Celebgramme');
      $message->to($user->email);
      $message->subject('Email Confirmation');
    });

    if (! $request->session()->has('checkout_data')) {
      return redirect('/home');
    } else {
      
      $checkout_data = $request->session()->get('checkout_data');
      $package = Package::find($checkout_data["package_id"]);
      
      if ($checkout_data["payment_method"]== 1) {
        $data = array (
          "order_type" => "transfer_bank",
          "order_status" => "pending",
          "user_id" => $user->id,
          "order_total" => $package->price,
        );
        
        $order = Order::createOrder($data);
        return redirect('/home');
      }
      
      if ($checkout_data["payment_method"]== 2) {
        
        // Validation passes
        $vt = new Veritrans;
        // Populate items
        $items = [];

        // package
        array_push($items, [
          'id' => '#Package',
          'price' => $package->price,
          'quantity' => 1,
          'name' => $package->package_name,
        ]);
        $totalPrice = $package->price;
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
              //'enabled_payments' => [],
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
      $request->session()->forget('checkout_data');
      
    }
	}
	

}
