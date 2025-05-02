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

Route::get('/', [App\Http\Controllers\Employee\Auth\LoginController::class, 'showLoginForm'])->name('login');


Route::group(['prefix' => 'admin'], function () {
  Route::get('/', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
  Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

  // Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  // Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm']);

});

Route::group(['prefix' => 'employee'], function () {
  Route::get('/', [App\Http\Controllers\Employee\Auth\LoginController::class, 'showLoginForm'])->name('employee.login');
  Route::get('/login', [App\Http\Controllers\Employee\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Employee\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Employee\Auth\LoginController::class, 'logout'])->name('logout');

  // Route::get('/register', [App\Http\Controllers\Employee\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  // Route::post('/register', [App\Http\Controllers\Employee\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Employee\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Employee\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Employee\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Employee\Auth\ResetPasswordController::class, 'showResetForm']);

});

Route::group(['prefix' => 'client'], function () {
  Route::get('/', [App\Http\Controllers\Client\Auth\LoginController::class, 'showLoginForm'])->name('client.login');
  Route::get('/login', [App\Http\Controllers\Client\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Client\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Client\Auth\LoginController::class, 'logout'])->name('logout');

  Route::get('/register', [App\Http\Controllers\Client\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  Route::post('/register', [App\Http\Controllers\Client\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Client\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Client\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Client\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Client\Auth\ResetPasswordController::class, 'showResetForm']);

});

Route::group(['prefix' => 'applicant'], function () {
  Route::get('/', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'showLoginForm'])->name('client.login');
  Route::get('/login', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'logout'])->name('logout');

  Route::get('/register', [App\Http\Controllers\Applicant\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  Route::post('/register', [App\Http\Controllers\Applicant\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Applicant\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Applicant\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Applicant\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Applicant\Auth\ResetPasswordController::class, 'showResetForm']);
});
