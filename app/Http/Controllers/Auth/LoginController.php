<?php

namespace Celebgramme\Http\Controllers\Auth;

use Celebgramme\Http\Controllers\Controller;
use Celebgramme\Models\User;
use Celebgramme\Models\Post;
use Illuminate\Http\Request;
use Celebgramme\Http\Requests\LoginFormRequest as loginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

use Input, Redirect, App, Socialite;
class LoginController extends Controller
{
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}
	
	/**
	 * Menampilkan halaman login
	 *
	 * @return response
	 */
	public function getLogin()
	{
		
		if (Auth::check()){
			return Redirect('/');
		}
		else{
			// return view('auth/login');
			$content = "";
			$post = Post::where("type","=","footer_ads")->first();
			if (!is_null($post)) {
				$content = $post->description;
			}

      return view("auth.login")->with(array(
          'content'=>$content,
          ));
		}
	}
	
  public function choose_tool(){
    return view("auth.choose-tools");
  }
	/**
	 * login kedalam aplikasi
	 *
	 * var loginRequest $request
	 *
	 * @return response
	 */
	public function postLogin(loginRequest $request)
	{
		$remember = (Input::has('remember')) ? true : false;
		$field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if(env('APP_PROJECT')=='Amelia'){
      $user = User::where('email',$request->username)->first();

      if($user->is_member_rico==0){
        return Redirect::to('/login')
        ->with(array("error"=>"Anda tidak terdaftar sebagai member amelia"));
      }
    }

		if (Auth::attempt([$field => $request->username, 'password' => $request->password], $remember)) {
			if (isset($request->r)){
				return redirect($request->r);
			}
			else{
				return redirect('/home');
			}
		} else {
			return Redirect::to('/login')
				->with(array("error"=>"Login anda salah"));
		}
	}
	
	/**
	 * logout
	 *
	 *
	 * @return response
	 */
	public function getLogout(Request $request)
	{
		$request->session()->flush();
		Auth::logout();
		return Redirect('/');
	}
	
	/**
	 * Show Socialite authentication page
	 *
	 *
	 * @return response
	 */
	public function redirectToProvider(Request $request, $provider)
	{
		if (isset($request->r)){
			$request->session()->put('redirect_url', $request->r);
		}
		return Socialite::driver($provider)->redirect();
	}
	
	public function handleProviderCallback(Request $request, $provider)
	{
		$user = Socialite::driver($provider)->user();
		$cust = User::where('email', '=', $user->email)->first();
		if ($cust == null){
			// IF NOT REGISTERED YET
			$user_data = [
				'username' => '',
				'email' => $user->getEmail(),
				'social_login' => true,
				'social_token' => $user->token,
			];
			
			$request->session()->put('oauth2_token', $user->token);
			return view('auth/register')->with('user_data', $user_data);
		}
		else{
			// ELSE REGISTERED ALREADY
			Auth::loginUsingId($cust->id);
			return redirect('/home');
		}
	}
}
