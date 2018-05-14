<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Route for Auth
Route::post('login', 'Auth\LoginController@mobileLogin');
Route::post('login-facebook', 'Auth\LoginController@facebookLogin');
Route::post('register', 'Auth\RegisterController@createFacebook');
Route::post('account', 'UserController@update_account');
Route::post('account/address/home', 'UserController@update_adress_home');
Route::post('account/address/company', 'UserController@update_adress_work');
Route::get('account/{id?}/refresh', function($id){
  $user = App\Models\User::where('id', '=', $id)->firstOrFail();
  $user['donasi'] = App\Models\Donations::where('user_id', '=', $user->id)->sum('donation');
  $user['transaksi'] = App\Models\Donations::where('user_id', '=', $user->id)->count();
  $user['address'] = App\Models\Address::where('user_id', '=', $user->id)->where('jenis', '=', 'rumah')->firstOrFail();
  return $user;
});
Route::post('account/{id}/edit','UserController@updateMobile');
Route::post('account/{id}/address','UserController@updateAddressMobile');
// Password
Route::post('account/password', 'UserController@update_password');
Route::get('filter', 'APIController@filter');

// Upload Avatar
Route::post('upload/avatar', 'UserController@upload_avatar');

// Route for campaign
Route::get('campaigns', 'APIController@campaigns');
Route::get('campaign/{id?}', 'APIController@campaign');
Route::get('campaign/{id?}/{slug?}','APIController@campaignDetail');
Route::get('donations/{id?}', function($id) {
  return App\Models\Donations::where('campaigns_id', '=', $id)->where('payment_status', '=', 'paid')->get();
});

// Route for magazines
Route::get('magazines', 'APIController@magazines');

// Route for user
Route::get('user', 'APIController@user');

// Route for amils
Route::get('amil', function() {
  return App\Models\Amils::all();
});

// Route for donate
Route::post('donate/{id}','DonationsController@sendMobile');
Route::post('transfer/{id}','DonationsController@transferMobile');

// Route for bank
Route::get('bank', function() {
  return App\Models\Banks::all();
});

// Route for topup
Route::post('topup', 'TopUpController@sendMobile');
Route::post('topup/transfer/{id}', 'TopUpController@transferMobile');

// Route for News
Route::get('news/{slug?}', function($slug) {
  return App\Models\News::where('type', '=', $slug)->get();
});

// Route for transaction history
Route::get('history/{id?}', function($id) {
    $donations = App\Models\Donations::where('user_id', '=', $id)->get();
    $donations->map(function ($donation){
      $campaign = App\Models\Campaigns::where('id', '=', $donation->campaigns_id)->first();
      $donation['campaign'] = $campaign;

      return $donation;
    });

    return $donations;
});

// Route for saldo history
Route::get('history/saldo/{id?}', function($id) {
  $deposits = App\Models\DepositLog::where('user_id', '=', $id)->get();

  return $deposits;
});

Route::any('cabang', 'APIController@cabang');
Route::any('kategori', 'APIController@kategori');
Route::any('akun-transaksi', 'APIController@akunTransaksi');