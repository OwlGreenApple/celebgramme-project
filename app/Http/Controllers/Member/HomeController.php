<?php

namespace App\Http\Controllers\Member;

/*Models*/

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use View;

class HomeController extends Controller
{
  
	/**
	 * Menampilkan halaman utama
	 *
	 * @return response
	 */
	public function index(){
		return view('member.send-like');
	}
  
	public function order(){
		return view('member.order');
	}
  
	public function send_like(){
		return view('member.send-like');
	}
	
	public function edit_profile(){
		return view('member.profile');
	}
	
	
}
