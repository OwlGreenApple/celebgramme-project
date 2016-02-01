<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* register package */
Route::get('package', 'LandingPageController@package');
Route::get('prices', 'LandingPageController@package');
Route::get('checkout/{id}', 'LandingPageController@checkout');
Route::get('checkout', 'LandingPageController@checkout');
Route::get('calculate-coupon', 'LandingPageController@calculate_coupon');
Route::post('process-package', 'LandingPageController@process_package');

/* LOGIN / LOGOUT */
Route::get('login', 'Auth\LoginController@getLogin');
Route::post('auth/login', ['as'=>'auth.login', 'uses'=> 'Auth\LoginController@postLogin']);
Route::get('logout', 'Auth\LoginController@getLogout');

/* register */
Route::get('register', 'Auth\RegisterController@getRegister');
Route::get('register-checkout', 'LandingPageController@register_checkout');
Route::post('auth/register', ['as'=>'auth.register', 'uses'=> 'Auth\RegisterController@postRegister']);

Route::get('verifyemail/{cryptedcode}', 'Member\EmailController@verifyEmail');

/* FORGOT PASSWORD */
Route::get('forgot-password', 'LandingPageController@forgot_password');
Route::get('redirect-auth/{cryptedcode}', 'LandingPageController@redirect_auth');
Route::post('auth/forgot', ['as'=>'auth.forgot', 'uses'=> 'LandingPageController@auth_forgot']);
Route::post('change-password', ['as'=>'change.password', 'uses'=> 'LandingPageController@change_password']);

/*--------- Must Login Routes ---------*/
Route::group(['middleware' => 'auth'], function()
{
	/* Super Admin page */
	Route::get('super-admin', 'Member\AdminController@user_list');
	Route::get('check-super/{id}', 'Member\AdminController@check_super');

  /* Pay with tweet */
  Route::get('confirm-paywithtweet/{cryptedcode}', 'Member\HomeController@confirm_paywithtweet');

  Route::get('free-trial', 'Member\HomeController@free_trial');
  Route::get('resend-activation', 'Member\EmailController@resendEmailActivation');

  Route::get('test', 'Member\HomeController@test');
  Route::get('home', 'Member\AutoManageController@index');
  Route::get('/', 'Member\AutoManageController@index');
  Route::get('order', 'Member\HomeController@order');
  
  Route::get('edit-profile', 'Member\HomeController@edit_profile');
  Route::post('change-profile', 'Member\HomeController@change_profile');
  
  Route::get('send-like', 'Member\HomeController@send_like');
  Route::post('process-like', 'Member\HomeController@process_like');

  Route::get('confirm-payment', 'Member\HomeController@confirm_payment');
  Route::post('process-payment', 'Member\HomeController@process_payment');

  
  Route::get('buy-more/{id}', 'Member\HomeController@buy_more');
  Route::get('buy-more', 'Member\HomeController@buy_more');
  /*--------- Payment ---------*/
  Route::group(['prefix' => 'payment'], function () {
    /*--------- Veritrans ---------*/
    Route::post('process', ['as' => 'vt.notif', 'uses' => 'Member\PaymentController@process']);
    Route::get('finish', ['as' => 'vt.finish', 'uses' => 'Member\PaymentController@veritransFinish']);
    Route::get('fail', ['as' => 'vt.fail', 'uses' => 'Member\PaymentController@veritransFail']);
  });
  Route::get('checkout-finish', 'Member\CheckoutController@checkout_finish');

  // Route::get('payment/paypal', 'Member\PaypalController@store');
  Route::resource('payment/paypal', 'Member\PaypalController');
  Route::get('payment/paypal/test', 'Member\PaypalController@test');
  

  /*--------- Auto-manage ---------*/
  Route::get('auto-manage', 'Member\AutoManageController@index');
  Route::get('load-account', 'Member\AutoManageController@load_account');
  Route::get('account-setting/{id}', 'Member\AutoManageController@account_setting');
  Route::post('process-save-credential', 'Member\AutoManageController@process_save_credential');
  Route::post('process-edit-password', 'Member\AutoManageController@process_edit_password');
  Route::post('process-save-setting', 'Member\AutoManageController@process_save_setting');
  Route::get('call-action', 'Member\AutoManageController@call_action');
  Route::post('delete-setting', 'Member\AutoManageController@delete_setting');

});


Route::post('payment/vtnotification', ['as' => 'vt.notif', 'uses' => 'Member\PaymentController@veritransNotification']);

Route::get('generate-balance', 'CronJobController@generate_balance');
Route::get('cron-auto-manage', 'CronJobController@auto_manage');
Route::get('cron-notif-member', 'CronJobController@notif_member');
Route::get('auto-follow-unfollow', 'CronJobController@auto_follow_unfollow');
Route::get('replace-delimiter', 'CronJobController@replace_delimiter');

