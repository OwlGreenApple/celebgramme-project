<?php
namespace Celebgramme\Helpers;

use DB, Crypt, App;

class GeneralHelper{

  /**
   * Get generated string from 1 Database Table
   *
   * @param $model MODELS
   * @param $field STRING field name
   * @param $field STRING field name
   *
   * @return string
   */
  public static function autoGenerateID($model, $field, $search, $pad_length, $pad_string = '0')
  {
    $tb = $model->select(DB::raw("substr(".$field.", ".$pad_length.") as lastnum"))
								->whereRaw("substr(".$field.", 1, ".count($search).") = '".$search."'")
								->orderBy('id', 'DESC')
								->first();
		if ($tb == null){
			$ctr = 1;
		}
		else{
			$ctr = intval($tb->lastnum);
		}
		return $search.str_pad($ctr, $pad_length, $pad_string, STR_PAD_LEFT);
  }
	
	public static function getReferralID()
  {
		$ref_id = 0;
		if (isset($_COOKIE['referral_id'])){
			$cookie_ref_id = $_COOKIE['referral_id'];
			$ref_id = intval(Crypt::decrypt($cookie_ref_id));
		}
		return $ref_id;
	}
	
	public static function getBaseDirectory()
  {
		if (App::environment() == 'production'){
			$path = base_path().'/../public_html';
		}
		else{
			$path = base_path().'/../htdocs';
		}
		return $path;
	}

	public static function trimStringToFullWord($string, $length)
	{
		if (strlen($string) <= $length) {
			$string = $string; //do nothing
		}
		else {
			$string = preg_replace('/\s+?(\S+)?$/u', '', substr($string, 0, $length));
		}
		return $string;
	}

	/**
	 * Send Phone confirmation code using Zenziva API
	 *
	 * @return response
	 */
	public static function zenziva_sendSMS($phone, $message)
	{
		$zUserKey = env('ZENZIVA_USERKEY');
		$zPassKey = env('ZENZIVA_PASSKEY');
		$zMessage = $message;
		$zURL = 'https://reguler.zenziva.net/apps/smsapi.php';
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $zURL);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$zUserKey.'&passkey='.$zPassKey.'&nohp='.$phone.'&pesan='.urlencode($zMessage));
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		$results = curl_exec($curlHandle);
		curl_close($curlHandle);
	}
}

?>
