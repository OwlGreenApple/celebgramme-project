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

Route::get('login', 'Auth\LoginController@getLogin');
Route::post('auth/login', ['as'=>'auth.login', 'uses'=> 'Auth\LoginController@postLogin']);
Route::get('logout', 'Auth\LoginController@getLogout');

Route::get('register', 'Auth\RegisterController@getRegister');
Route::post('auth/register', ['as'=>'auth.register', 'uses'=> 'Auth\RegisterController@postRegister']);

/*--------- Must Login Routes ---------*/
Route::group(['middleware' => 'auth'], function()
{
  Route::get('test', 'Member\HomeController@test');
  Route::get('home', 'Member\HomeController@index');
  Route::get('/', 'Member\HomeController@index');
  Route::get('order', 'Member\HomeController@order');
  Route::get('send-like', 'Member\HomeController@send_like');
  
  Route::get('edit-profile', 'Member\HomeController@edit_profile');
  Route::post('process-like', 'Member\HomeController@process_like');
  
  Route::get('buy-more', 'Member\HomeController@buy_more');
  /*--------- Payment ---------*/
  Route::group(['prefix' => 'payment'], function () {
    /*--------- Veritrans ---------*/
    Route::post('veritransredirect', ['as' => 'vt.notif', 'uses' => 'Member\PaymentController@veritransRedirect']);
    Route::get('finish', ['as' => 'vt.finish', 'uses' => 'Member\PaymentController@veritransFinish']);
    Route::get('fail', ['as' => 'vt.fail', 'uses' => 'Member\PaymentController@veritransFail']);
  });
  Route::get('checkout-finish', 'Member\CheckoutController@checkout_finish');
  
});
  Route::post('payment/vtnotification', ['as' => 'vt.notif', 'uses' => 'Member\PaymentController@veritransNotification']);
