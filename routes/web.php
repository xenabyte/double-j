<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'Admin\Auth\LoginController@login');
  Route::post('/logout', 'Admin\Auth\LoginController@logout')->name('logout');

  Route::get('/register', 'Admin\Auth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'Admin\Auth\RegisterController@register');

  Route::post('/password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'Admin\Auth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'employee'], function () {
  Route::get('/login', 'Employee\Auth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'Employee\Auth\LoginController@login');
  Route::post('/logout', 'Employee\Auth\LoginController@logout')->name('logout');

  Route::get('/register', 'Employee\Auth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'Employee\Auth\RegisterController@register');

  Route::post('/password/email', 'Employee\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'Employee\Auth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'Employee\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'Employee\Auth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'client'], function () {
  Route::get('/login', 'Client\Auth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'Client\Auth\LoginController@login');
  Route::post('/logout', 'Client\Auth\LoginController@logout')->name('logout');

  Route::get('/register', 'Client\Auth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'Client\Auth\RegisterController@register');

  Route::post('/password/email', 'Client\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'Client\Auth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'Client\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'Client\Auth\ResetPasswordController@showResetForm');
});
