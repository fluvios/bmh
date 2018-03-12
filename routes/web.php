<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
 |-----------------------------------
 | Index
 |-----------------------------------
 */
Route::get('/', 'HomeController@index');

Route::get('home', function () {
    return redirect('/');
});


/*
 |
 |-----------------------------------
 | Search
 |--------- -------------------------
 */
Route::get('search', 'HomeController@search');

/*
 |
 |-----------------------------------
 | Categories List
 |--------- -------------------------
 */
Route::get('category/{slug}', 'HomeController@category');

// Categories
Route::get('categories', function () {
    $data = App\Models\Categories::where('mode', 'on')->where('slug', '!=', 'public')->orderBy('name')->get();

    return view('default.categories')->withData($data);
});

/*
 |
 |-----------------------------------
 | Kategori List
 |--------- -------------------------
 */
Route::get('kategori/{slug}', 'HomeController@kategori');

// Kategori
Route::get('kategori', function () {
    $data = App\Models\Kategori::where('is_active', 1)->orderBy('nama')->get();

    return view('default.kategori-list')->withData($data);
});

/*
 |
 |-----------------------------------
 | Cabang List
 |--------- -------------------------
 */
Route::get('cabang/{kode}', 'HomeController@cabang');

// Kategori
Route::get('cabang', function () {
    $data = App\Models\Cabang::orderBy('nama')->get();

    return view('default.cabang-list')->withData($data);
});

/*
 |
 |-----------------------------------
 | Verify Account
 |--------- -------------------------
 */
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code', '[A-Za-z0-9]+');

/*

/*
 |
 |-----------------------------------
 | Referral
 |--------- -------------------------
 */
Route::get('ref/donasi/{email}/{id}', 'ReferralController@refferalDonasi')->middleware('auth');
Route::get('ref/register/{email}', 'ReferralController@refferalRegistrasi');
/*

/*
 |-----------------------------------
 | Authentication
 |-----------------------------------
 */
Auth::routes();

// Logout
Route::get('/logout', 'Auth\LoginController@logout');

/*
 |
 |-----------------------------------------------
 | Ajax Request
 |--------- -------------------------------------
 */
Route::get('ajax/donations', 'AjaxController@donations');
Route::get('ajax/campaign/updates', 'AjaxController@updatesCampaign');
Route::get('ajax/campaigns', 'AjaxController@campaigns');
Route::get('ajax/category', 'AjaxController@category');
Route::get('ajax/tags', function () {
    return \App\Models\Categories::where('is_funding_type', 'no')->get();
});
Route::get('ajax/search', 'AjaxController@search');

/*
 |
 |-----------------------------------
 | Contact Organizer
 |-----------------------------------
 */

Route::post('contact/organizer', 'CampaignsController@contactOrganizer');

/*
 |
 |-----------------------------------
 | Details Campaign
 |--------- -------------------------
 */
Route::get('campaign/{id}/{slug?}', [
   'as' => 'campaign-detail-page',
   'uses' => 'CampaignsController@view',
]);

/*
 |
 |-----------------------------------
 | User Views LOGGED
 |--------- -------------------------
 */
Route::group(['middleware' => 'auth'], function () {

    //<-------------- Create Campaign
    Route::get('create/campaign', function () {
        return view('campaigns.create');
    });
    //  Post
    Route::post('create/campaign', 'CampaignsController@create');

    //<-------------- Edit Campaign
    Route::get('edit/campaign/{id}', 'CampaignsController@edit');
    Route::post('edit/campaign/{id}', 'CampaignsController@post_edit');

    //<-------------- Post a Update
    Route::get('update/campaign/{id}', 'CampaignsController@update');
    Route::post('update/campaign/{id}', 'CampaignsController@post_update');

    //<-------------- Edit post a Update
    Route::get('edit/update/{id}', 'CampaignsController@edit_update');
    Route::post('edit/update/{id}', 'CampaignsController@post_edit_update');

    Route::post('delete/image/updates', 'CampaignsController@delete_image_update');

    // Delete Campaign
    Route::get('delete/campaign/{id}', 'CampaignsController@delete');

    // Withdrawal
    Route::get('account/withdrawals', 'CampaignsController@show_withdrawal');
    Route::post('campaign/withdrawal/{id}', 'CampaignsController@withdrawal');

    Route::get('account/withdrawals/configure', function () {
        return view('users.withdrawals-configure');
    });

    Route::post('withdrawals/configure/{type}', 'CampaignsController@withdrawalConfigure');

    Route::post('delete/withdrawal/{id}', 'CampaignsController@withdrawalDelete');

    // Account Settings
    Route::get('dashboard', 'UserController@dashboard');
    Route::get('account', 'UserController@account');
    Route::post('account', 'UserController@update_account');
    Route::get('account/topup', 'UserController@topup');
    Route::get('account/referral', 'UserController@referral');
    Route::post('account/address/home', 'UserController@update_adress_home');
    Route::post('account/address/company', 'UserController@update_adress_work');

    // Password
    Route::get('account/password', 'UserController@password');
    Route::post('account/password', 'UserController@update_password');

    // Upload Avatar
    Route::post('upload/avatar', 'UserController@upload_avatar');

    // Campaigns
    Route::get('account/campaigns', function () {
        $_data = App\Models\Campaigns::where('user_id', Auth::user()->id)
     ->orderBy('id', 'DESC')
     ->paginate(20);

        // Deadline
        $timeNow = strtotime(Carbon\Carbon::now());

        foreach ($_data as $key) {
            if ($key->deadline != '') {
                $_deadline = strtotime($key->deadline);

                // if ($_deadline < $timeNow && $key->finalized == '0') {
                //     $sql = App\Models\Campaigns::find($key->id);
                //     $sql->finalized = '1';
                //     $sql->save();
                // }
            }
        }

        $data = App\Models\Campaigns::where('user_id', Auth::user()->id)
     ->orderBy('id', 'DESC')
     ->paginate(20);

        return view('users.campaigns')->withData($data);
    });

    // Donations
    Route::get('account/donations', function () {
        return view('users.donations');
    });

    // Report Campaign
    Route::get('report/campaign/{id}/{user}', 'CampaignsController@report');

    // Ajax Like
    Route::post('ajax/like', 'AjaxController@like');

    // User Likes
    Route::get('user/likes', 'UserController@likes');
});
/*
 |
 |-----------------------------------
 | Admin Panel
 |--------- -------------------------
 */
Route::group(['middleware' => 'role'], function () {

    // Upgrades
    Route::get('update/{version}', 'UpgradeController@update');

    // Dashboard
    Route::get('panel/admin', 'AdminController@admin');

    // Settings
    Route::get('panel/admin/settings', 'AdminController@settings');
    Route::post('panel/admin/settings', 'AdminController@saveSettings');

    // TopUp
    Route::get('panel/admin/top_up', 'TopUpController@showdata');
    Route::get('panel/admin/top_up/{id}', 'TopUpController@topupview');
    Route::get('panel/admin/top_up/status/accept/{id}', 'TopUpController@topupAccept');
    Route::get('panel/admin/top_up/status/reject/{id}', 'TopUpController@topupReject');

    // Limits
    Route::get('panel/admin/settings/limits', 'AdminController@settingsLimits');
    Route::post('panel/admin/settings/limits', 'AdminController@saveSettingsLimits');

    // banks
    Route::get('panel/admin/settings/bank', 'AdminController@bank');
    Route::get('panel/admin/settings/bank/add', 'AdminController@addBank');
    Route::post('panel/admin/settings/bank/add', 'AdminController@storeBank');

    // Amils
    Route::get('panel/admin/amils', 'AdminController@amils');
    Route::get('panel/admin/amils/add', 'AdminController@addAmils');
    Route::post('panel/admin/amils/add', 'AdminController@storeAmils');

    // Campaigns
    Route::get('panel/admin/campaigns', 'AdminController@campaigns');
    Route::post('panel/admin/campaigns', 'AdminController@saveCampaigns');

    // Edit Campaign
    Route::get('panel/admin/campaigns/edit/{id}', 'AdminController@editCampaigns');
    Route::post('panel/admin/campaigns/edit/{id}', 'AdminController@postEditCampaigns');

    //Withdrawals
    Route::get('panel/admin/withdrawals', 'AdminController@withdrawals');
    Route::get('panel/admin/withdrawal/{id}', 'AdminController@withdrawalsView');
    Route::post('panel/admin/withdrawals/paid/{id}', 'AdminController@withdrawalsPaid');

    Route::post('paypal/withdrawal/ipn', 'AdminController@withdrawlsIpn');


    // Delete Campaign
    Route::post('panel/admin/campaign/delete', 'AdminController@deleteCampaign');

    // Donations
    Route::get('panel/admin/donations', 'AdminController@donations');
    Route::get('panel/admin/donations/{id}', 'AdminController@donationView');
    Route::get('panel/admin/donations/status/accept/{id}', 'AdminController@donationAccept');
    Route::get('panel/admin/donations/status/reject/{id}', 'AdminController@donationReject');

    // Members
    Route::resource(
        'panel/admin/members',
        'AdminController',
        ['names' => [
            'edit'    => 'user.edit',
            'destroy' => 'user.destroy'
         ]]
    );

    // Add Member
    Route::get('panel/admin/member/add', 'AdminController@add_member');
    Route::post('panel/admin/member/add', 'AdminController@storeMember');

    // Pages
    Route::resource(
        'panel/admin/pages',
        'PagesController',
        ['names' => [
            'edit'    => 'pages.edit',
            'destroy' => 'pages.destroy'
         ]]
    );

    // Payments Settings
    Route::get('panel/admin/payments', 'AdminController@payments');
    Route::post('panel/admin/payments', 'AdminController@savePayments');

    // Profiles Social
    Route::get('panel/admin/profiles-social', 'AdminController@profiles_social');
    Route::post('panel/admin/profiles-social', 'AdminController@update_profiles_social');

    // Admin categories
    Route::get('panel/admin/categories', 'AdminController@categories');
    Route::get('panel/admin/categories/add', 'AdminController@addCategories');
    Route::post('panel/admin/categories/add', 'AdminController@storeCategories');
    Route::get('panel/admin/categories/edit/{id}', 'AdminController@editCategories')->where(array( 'id' => '[0-9]+'));
    Route::post('panel/admin/categories/update', 'AdminController@updateCategories');
    Route::get('panel/admin/categories/delete/{id}', 'AdminController@deleteCategories')->where(array( 'id' => '[0-9]+'));

    // Campaigns Reported
    Route::get('panel/admin/campaigns/reported', 'AdminController@reportedCampaigns');
    Route::post('panel/admin/campaigns/reported/delete', 'AdminController@reportedDeleteCampaigns');

    Route::get('panel/admin/settings/cabang', [
        'as'    => 'admin-cabang-index',
        'uses'  => 'CabangController@index'
    ]);

    Route::get('panel/admin/settings/cabang/{id}/edit', [
        'as'    => 'admin-cabang-edit',
        'uses'  => 'CabangController@edit'
    ]);

    Route::post('panel/admin/settings/cabang/{id}/update', [
        'as'    => 'admin-cabang-update',
        'uses'  => 'CabangController@update'
    ]);

    Route::get('panel/admin/settings/cabang/add', [
        'as'    => 'admin-cabang-create',
        'uses'  => 'CabangController@add'
    ]);

    Route::post('panel/admin/settings/cabang/store', [
        'as'    => 'admin-cabang-store',
        'uses'  => 'CabangController@store'
    ]);

    Route::get('panel/admin/settings/kategori', [
        'as'    => 'admin-kategori-index',
        'uses'  => 'KategoriController@index'
    ]);

    Route::get('panel/admin/settings/kategori/{id}/edit', [
        'as'    => 'admin-kategori-edit',
        'uses'  => 'KategoriController@edit'
    ]);

    Route::post('panel/admin/settings/kategori/{id}/update', [
        'as'    => 'admin-kategori-update',
        'uses'  => 'KategoriController@update'
    ]);

    Route::get('panel/admin/settings/kategori/add', [
        'as'    => 'admin-kategori-create',
        'uses'  => 'KategoriController@add'
    ]);

    Route::post('panel/admin/settings/kategori/store', [
        'as'    => 'admin-kategori-store',
        'uses'  => 'KategoriController@store'
    ]);

    // Slider

    Route::get('panel/admin/settings/slider', [
        'as'    => 'admin-slider-index',
        'uses'  => 'SliderController@index'
    ]);

    Route::get('panel/admin/settings/slider/{id}/edit', [
        'as'    => 'admin-slider-edit',
        'uses'  => 'SliderController@edit'
    ]);

    Route::post('panel/admin/settings/slider/{id}/update', [
        'as'    => 'admin-slider-update',
        'uses'  => 'SliderController@update'
    ]);

    Route::get('panel/admin/settings/slider/add', [
        'as'    => 'admin-slider-create',
        'uses'  => 'SliderController@add'
    ]);

    Route::post('panel/admin/settings/slider/store', [
        'as'    => 'admin-slider-store',
        'uses'  => 'SliderController@store'
    ]);

    // Kode Akun
    Route::get('panel/admin/settings/akun-transaksi', [
        'as'    => 'admin-akun-transaksi-index',
        'uses'  => 'AkunTransaksiController@index'
    ]);

    Route::get('panel/admin/settings/akun-transaksi/{id}/edit', [
        'as'    => 'admin-akun-transaksi-edit',
        'uses'  => 'AkunTransaksiController@edit'
    ]);

    Route::post('panel/admin/settings/akun-transaksi/{id}/update', [
        'as'    => 'admin-akun-transaksi-update',
        'uses'  => 'AkunTransaksiController@update'
    ]);

    Route::get('panel/admin/settings/akun-transaksi/add', [
        'as'    => 'admin-akun-transaksi-create',
        'uses'  => 'AkunTransaksiController@add'
    ]);

    Route::post('panel/admin/settings/akun-transaksi/store', [
        'as'    => 'admin-akun-transaksi-store',
        'uses'  => 'AkunTransaksiController@store'
    ]);
});

/*
 |
 |-----------------------------------
 | Donations
 |--------- -------------------------
 */
Route::get('donate/{id}/{slug?}', 'DonationsController@show')->middleware('auth');
Route::post('donate/{id}', 'DonationsController@send');
Route::post('topup/{id}', 'TopUpController@send');
Route::get('transfer/{id}', function ($id) {
    return view('default.transfer')->withResponse($id);
});
Route::get('deposit/{id}', function ($id) {
    return view('default.deposit')->withResponse($id);
});
Route::get('transfer_topup/{id}', function ($id) {
    return view('users.transfer')->withResponse($id);
});
Route::post('transfer/{id}', 'DonationsController@transfer');
Route::post('deposit/{id}', function ($id) {
    $donation = App\Models\Donations::where('id', '=', $id)->firstOrFail();

    return response()->json([
    'success' => true,
    'stripeSuccess' => true,
    'url' => url('paypal/donation/success', $donation->campaigns_id)
  ]);
});
Route::post('transfer_topup/{id}', 'TopUpController@transfer');

// Paypal IPN
Route::post('paypal/ipn', 'DonationsController@paypalIpn');


Route::get('paypal/donation/success/{id}', function ($id) {
    session()->put('donation_success', trans('misc.donation_success'));
    return redirect("campaign/".$id);
});

Route::get('topup/success', function () {
    session()->put('donation_success', trans('misc.donation_success'));
    return redirect("account/topup");
});


Route::get('paypal/donation/cancel/{id}', function ($id) {
    session()->put('donation_cancel', trans('misc.donation_cancel'));
    return redirect("campaign/".$id);
});


/**
 * Midtrans IPN
 */
Route::post('midtrans/notification', 'DonationsController@midtransIpn');


/*
 |
 |------------------------
 | Pages Static Custom
 |--------- --------------
 */
Route::get('page/{page}', function ($page) {
    $response = App\Models\Pages::where('slug', '=', $page)->first();

    $total = count($response);

    if ($total == 0) {
        abort(404);
    } else {
        $title = $response->title.' - ';
        return view('pages.home', compact('response', 'title'));
    }
})->where('page', '[^/]*');


/*
 |
 |------------------------
 | Embed Widget
 |--------- --------------
 */
Route::get('c/{id}/widget.js', function ($id) {
    $iframeUrl = url('c', $id).'/widget/show';
    return 'var html = \'<iframe align="middle" scrolling="no" width="100%" height="550" frameBorder="0" src="'.$iframeUrl.'"></iframe>\'; document.write( html );';
});

// Embed Widget iFrame
Route::get('c/{id}/widget/show', function ($id) {
    $response = App\Models\Campaigns::where('id', $id)->where('status', 'active')->firstOrFail();
    return view('includes.embed')->withResponse($response);
});


// Test email
Route::get('/email-test/{id}', 'DonationsController@emailTest');
