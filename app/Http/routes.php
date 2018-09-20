<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
| its version 2.
*/

/*API connect dengan admin*/
Route::get('get-username-available/{username}', 'LandingPageController@get_username_available');

/* Survey */
Route::get('survey', 'LandingPageController@survey');
Route::post('submit-survey', 'LandingPageController@submit_survey');

/* register package */
Route::get('testing', 'LandingPageController@testing');
Route::get('package', 'LandingPageController@package');
Route::get('prices', 'LandingPageController@package');
Route::get('checkout/{id}', 'LandingPageController@checkout');
Route::get('checkout', 'LandingPageController@checkout'); //??
Route::get('calculate-coupon', 'LandingPageController@calculate_coupon');
Route::post('process-package', 'LandingPageController@process_package');

/* LOGIN / LOGOUT */
Route::get('/', 'Auth\LoginController@getLogin');
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

Route::get('logs-731', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

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

  // Route::get('test', 'Member\HomeController@test');
  // Route::get('home', 'Member\AutoManageController@index');
  // Route::get('/', 'Member\AutoManageController@index');
  Route::get('order', 'Member\HomeController@order');
  
  Route::get('edit-profile', 'Member\HomeController@edit_profile');
  Route::post('change-profile', 'Member\HomeController@change_profile');
  
  Route::get('send-like', 'Member\HomeController@send_like');
  Route::post('process-like', 'Member\HomeController@process_like');

  Route::get('confirm-payment', 'Member\HomeController@confirm_payment');
  Route::get('confirm-payment/{no_order}', 'Member\HomeController@confirm_payment');
  Route::post('delete-order', 'Member\HomeController@delete_order');
  Route::get('get-payment-total', 'Member\HomeController@get_payment_total');
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
  // Route::get('load-account', 'Member\AutoManageController@load_account');
  Route::get('account-setting/{id}', 'Member\AutoManageController@account_setting');
  Route::post('process-save-credential', 'Member\AutoManageController@process_save_credential');
  Route::post('process-edit-password', 'Member\AutoManageController@process_edit_password');
  Route::post('process-save-setting', 'Member\AutoManageController@process_save_setting');
  Route::get('call-action', 'Member\AutoManageController@call_action');
  Route::post('delete-setting', 'Member\AutoManageController@delete_setting');
	Route::get('agree-terms', 'Member\AutoManageController@agree_terms');
	Route::get('check-message', 'Member\AutoManageController@check_message');
	// Route::get('action-direct-message', 'Member\AutoManageController@action_direct_message');
	
	
	/*--------- New-dashboard ---------*/
	// Route::get('/', 'Member\NewDashboardController@index');
	Route::get('home', 'Member\NewDashboardController@index');
	Route::get('dashboard', 'Member\NewDashboardController@dashboard');
	Route::get('load-account', 'Member\NewDashboardController@load_account');
	Route::get('setting/{id}', 'Member\NewDashboardController@setting_index');
	Route::get('get-chat-all', 'Member\NewDashboardController@get_chat_all');
	Route::get('action-direct-message', 'Member\NewDashboardController@action_direct_message');
	Route::get('get-dm-req', 'Member\NewDashboardController@get_dm_req');
	Route::get('action-dm-req', 'Member\NewDashboardController@action_dm_req');
	Route::get('get-dm-inbox', 'Member\NewDashboardController@get_dm_inbox');
	Route::get('save-welcome-message', 'Member\NewDashboardController@save_welcome_message');
	Route::get('auto-complete-username', 'Member\NewDashboardController@get_username');
	Route::get('test', 'Member\NewDashboardController@test');
	
	Route::post('submit-auto-responder', 'Member\NewDashboardController@submit_auto_responder');
	Route::get('get-auto-responder', 'Member\NewDashboardController@get_auto_responder');
	Route::get('delete-auto-responder', 'Member\NewDashboardController@delete_auto_responder');
});


Route::post('payment/vtnotification', ['as' => 'vt.notif', 'uses' => 'Member\PaymentController@veritransNotification']);

/*Active cron*/
Route::get('generate-balance', 'CronJobController@generate_balance'); // unused
Route::get('reset-client-used', 'CronJobController@reset_client_used'); // unused
Route::get('cron-auto-manage-369', 'CronJobController@auto_manage');
Route::get('cron-notif-member-369', 'CronJobController@notif_member');
Route::get('count-instagram-data-369/{insta_username}', 'CronJobController@count_instagram_data');
// ganti di automation Route::get('count-instagram-data-369', 'CronJobController@count_instagram_data');
Route::get('check-create-affiliate-369', 'CronJobController@create_user_from_affiliate');
Route::get('task-daily-automation-cron-369', 'CronJobController@task_daily_automation_cron');
Route::get('check-all-proxy-369', 'CronJobController@check_all_proxy');

//instant changing 
Route::get('fixing-error-cred', 'CronJobController@fixing_error_cred');

/*needed for update databasw*/
Route::get('update-insta-user-id', 'CronJobController@update_insta_user_id');
Route::get('replace-delimiter', 'CronJobController@replace_delimiter');


/* DOKU PAYMENT */
Route::get('process-doku', 'Member\PaymentController@process_doku');
Route::post('notification-doku', 'Member\PaymentController@notification_doku');
Route::any('doku-page/{action}', 'Member\PaymentController@doku_page');

/* IDAFF */
Route::get('postback-idaff', 'LandingPageController@post_back_idaff');

/* API */
Route::get('get-photo-hashtags/{hashtags}', 'LandingPageController@get_photo_hashtags');
Route::get('get-photo-hashtags/{hashtags}/{cursor}', 'LandingPageController@get_photo_hashtags');
Route::get('get-proxy-id/{insta_username}', 'LandingPageController@get_proxy_id');

// Route::get('fixing-error', 'LandingPageController@fixing_error');

Route::get('check-instagram-username/{insta_username}', 'LandingPageController@check_instagram_username');


