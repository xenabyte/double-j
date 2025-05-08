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

  Route::post('/updateSiteInfo', [App\Http\Controllers\Admin\AdminController::class, 'updateSiteInfo'])->name('updateSiteInfo')->middleware(['auth:admin']);
  
  Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home')->middleware(['auth:admin']);
  Route::get('/siteSettings', [App\Http\Controllers\Admin\AdminController::class, 'siteSettings'])->name('siteSettings')->middleware(['auth:admin']);


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

  Route::get('/home', [App\Http\Controllers\Employee\EmployeeController::class, 'index'])->name('home')->middleware(['auth:employee']);

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

  Route::get('/home', [App\Http\Controllers\Client\ClientController::class, 'index'])->name('home')->middleware(['auth:client']);

});

Route::group(['prefix' => 'applicant'], function () {
  Route::get('/', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'showLoginForm'])->name('applicant.login');
  Route::get('/login', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Applicant\Auth\LoginController::class, 'logout'])->name('logout');

  Route::get('/register', [App\Http\Controllers\Applicant\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  Route::post('/register', [App\Http\Controllers\Applicant\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Applicant\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Applicant\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Applicant\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Applicant\Auth\ResetPasswordController::class, 'showResetForm']);

  Route::get('/home', [App\Http\Controllers\Applicant\ApplicantController::class, 'index'])->name('home')->middleware(['auth:applicant']);

  Route::get('/biodata', [App\Http\Controllers\Applicant\ApplicantController::class, 'biodata'])->name('biodata')->middleware(['auth:applicant']);
  Route::post('/updateBiodata', [App\Http\Controllers\Applicant\ApplicantController::class, 'updateBiodata'])->name('updateBiodata')->middleware(['auth:applicant']);

});
