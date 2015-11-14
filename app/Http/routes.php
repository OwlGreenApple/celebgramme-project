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
  Route::get('home', 'Member\HomeController@index');
  Route::get('/', 'Member\HomeController@index');
  Route::get('order', 'Member\HomeController@order');
  Route::get('send-like', 'Member\HomeController@send_like');
});
