<?php

namespace Celebgramme\Http\Controllers\Member;

/*Models*/

use Celebgramme\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\RequestModel;
use Celebgramme\Models\Invoice;
use Celebgramme\Models\Order;
use Celebgramme\Models\OrderMeta;
use Celebgramme\Models\User;
use Celebgramme\Veritrans\Veritrans;

use View, Input, Mail, Request, App, Hash, Validator, Carbon, Crypt, Redirect;

class EmailController extends Controller
{
  
  /**
   * Mengirim ulang email aktivasi
   *
   * @return response
   */
  public function resendEmailActivation()
  {
    $user = Auth::user();
    
    $register_time = Carbon::now()->toDateTimeString();
    $verificationcode = Hash::make($user->email.$register_time);
    $user->verification_code = $verificationcode;
    $user->save();
    if (App::environment() == 'local'){
      $url = 'http://localhost/celebgramme/public/verifyemail/';
    }
    else if (App::environment() == 'production'){
      $url = url('verifyemail');
    }
    $secret_data = [
      'email' => $user->email,
      'register_time' => $register_time,
      'verification_code' => $verificationcode,
    ];
    $emaildata = [
      'url' => $url.Crypt::encrypt(json_encode($secret_data)),
			'user' => $user,
			'password' => "",
    ];
    Mail::queue('emails.confirm-email', $emaildata, function ($message) use ($user) {
      $message->from('no-reply@activfans.com', 'Activfans');
      $message->to($user->email);
      $message->subject('Email Confirmation');
    });
    $arr = array (
      "message"=>"Email aktivasi berhasil dikirim",
      "type"=>"success",
      );
    return $arr;
  }
 
  public function verifyEmail($cryptedcode)
  {
      try {
        $decryptedcode = Crypt::decrypt($cryptedcode);
        $data = json_decode($decryptedcode);
        $user = User::where("email","=",$data->email)->first();
        if (!is_null($user)) {
          // Check customer email and status
          if ($user->type == 'not-confirmed'){
            // Check Verification Code
            if ($user->verification_code == $data->verification_code){
              $reg_date = Carbon::createFromFormat('Y-m-d H:i:s', $data->register_time);
                // Change customer status to verified, then redirect to Home
                $user->type = 'confirmed-email';
                $user->save();

                return Redirect::to("http://activfans.com/thank-you-page/");
                // return redirect('/')->with('message', [
                //   'title' => 'Aktivasi Berhasil',
                //   'content' => 'Terima kasih telah melakukan konfirmasi email. Akun Anda telah diaktifkan.',
                // ]);
            }
            else{
              return redirect(404);
            }
          }
          else{
            return redirect(404);
          }
        }
        else{
          return redirect(404);
        }
      } catch (DecryptException $e) {
        return redirect(404);
      }
  }

  
}
