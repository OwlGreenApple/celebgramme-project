<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;

use Input, Redirect, App, Hash, Mail, Crypt;

class RegisterController extends Controller
{
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
		// $allRequest = $request->all();
    $validator  = User::validator($request->all());
    if (!$validator->fails()){
      User::create($request->all());
    } else {
      return "data tidak valid";
    }
    return "asd";
	}
	
}
