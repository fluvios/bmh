<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Amils;
use App\Models\Locations;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Kabupaten;
use App\Models\Banks;
use App\Models\CampaignsReported;
use App\Models\Donations;
use App\Models\DonationsLog;
use App\Models\ReferralDonasi;
use App\Models\Categories;
use App\Models\Withdrawals;
use App\Models\KategoriCampaign;
use App\Helper;

use Carbon\Carbon;
use Fahim\PaypalIPN\PaypalIPNListener;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Mail;

class AdminController extends Controller
{
    public function __construct(AdminSettings $settings)
    {
        $this->settings = $settings::first();
    }

    public function index(Request $request)
    {
        $query = $request->input('q');

        if ($query != '' && strlen($query) > 2) {
            $data = User::where('name', 'LIKE', '%'.$query.'%')
            ->orderBy('id', 'desc')->paginate(20);
        } else {
            $data = User::orderBy('id', 'desc')->paginate(20);
        }

        return view('admin.members', ['data' => $data,'query' => $query]);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        if ($data->id == 1 || $data->id == Auth::user()->id) {
            \Session::flash('info_message', trans('admin.user_no_edit'));
            return redirect('panel/admin/members');
        }
        return view('admin.edit-member')->withData($data);
    }//<--- End Method

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $input = $request->all();



        if (!empty($request->password)) {
            $rules = array(

            'name' => 'required|min:3|max:25',

            'email'     => 'required|email|unique:users,email,'.$id,

             'password' => 'min:6',

            );



            $password = \Hash::make($request->password);
        } else {
            $rules = array(

            'name' => 'required|min:3|max:25',

            'email'     => 'required|email|unique:users,email,'.$id,

            );



            $password = $user->password;
        }



        $this->validate($request, $rules);



        $user->name = $request->name;

        $user->email = $request->email;

        $user->role = $request->role;

        $user->password = $password;

        $user->save();



        \Session::flash('success_message', trans('admin.success_update'));



        return redirect('panel/admin/members');
    }//<--- End Method



    public function destroy($id)
    {
        $user = User::findOrFail($id);



        if ($user->id == 1 || $user->id == Auth::user()->id) {
            return redirect('panel/admin/members');

            exit;
        }



        // Find User



        // Stop Campaigns

        $allCampaigns = Campaigns::where('user_id', $id)->update(array('finalized' => '1'));



        //<<<-- Delete Avatar -->>>/

        $fileAvatar    = 'public/avatar/'.$user->avatar;



        if (\File::exists($fileAvatar) && $user->avatar != 'default.jpg') {
            \File::delete($fileAvatar);
        }//<--- IF FILE EXISTS





        $user->delete();

        return redirect('panel/admin/members');
    }//<--- End Method



    public function add_member()
    {
        return view('admin.add-member');
    }

    public function storeMember(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'email'     => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->avatar = 'default.jpg';
        $user->token = str_random(80);
        $user->password = \Hash::make($request->password);
        $user->save();

        \Session::flash('success_message', trans('admin.success_add'));
        return redirect('panel/admin/members');
    }

    // START

    public function admin()
    {
        return view('admin.dashboard');
    }//<--- END METHOD



    public function settings()
    {
        return view('admin.settings')->withSettings($this->settings);
    }//<--- END METHOD



    public function saveSettings(Request $request)
    {
        $rules = array(

            'title'            => 'required',

            'welcome_text' 	   => 'required',

            'welcome_subtitle' => 'required',

            'keywords'         => 'required',

            'description'      => 'required',

            'email_no_reply'   => 'required',

            'email_admin'      => 'required',

            'harga_emas'      => 'required',

            'harga_beras'      => 'required',

            'bonus_registrasi'      => 'required',

            'bonus_donasi'      => 'required',
        );



        $this->validate($request, $rules);



        $sql                      = AdminSettings::first();

        $sql->title               = $request->title;

        $sql->welcome_text        = $request->welcome_text;

        $sql->welcome_subtitle    = $request->welcome_subtitle;

        $sql->keywords            = $request->keywords;

        $sql->description         = $request->description;

        $sql->email_no_reply      = $request->email_no_reply;

        $sql->email_admin         = $request->email_admin;

        $sql->auto_approve_campaigns = $request->auto_approve_campaigns;

        $sql->captcha                = $request->captcha;

        $sql->email_verification = $request->email_verification;

        $sql->harga_beras = $request->harga_beras;

        $sql->harga_emas = $request->harga_emas;

        $sql->registration_bonus = $request->bonus_registrasi;

        $sql->donation_bonus = $request->bonus_donasi;

        $sql->save();



        \Session::flash('success_message', trans('admin.success_update'));



        return redirect('panel/admin/settings');
    }//<--- END METHOD



    public function settingsLimits()
    {
        return view('admin.limits')->withSettings($this->settings);
    }//<--- END METHOD



    public function saveSettingsLimits(Request $request)
    {
        $rules = array(

            'min_campaign_amount'             => 'required|integer|min:0',

            'max_campaign_amount'             => 'required|integer|min:0',

            'min_donation_amount'             => 'required|integer|min:0',

            'max_donation_amount'             => 'required|integer|min:0',

        );



        $this->validate($request, $rules);



        $sql                      = AdminSettings::first();

        $sql->result_request      = $request->result_request;

        $sql->file_size_allowed   = $request->file_size_allowed;

        $sql->min_campaign_amount   = $request->min_campaign_amount;

        $sql->max_campaign_amount   = $request->max_campaign_amount;

        $sql->min_donation_amount   = $request->min_donation_amount;

        $sql->max_donation_amount   = $request->max_donation_amount;



        $sql->save();



        \Session::flash('success_message', trans('admin.success_update'));



        return redirect('panel/admin/settings/limits');
    }//<--- END METHOD



    public function profiles_social()
    {
        return view('admin.profiles-social')->withSettings($this->settings);
    }//<--- End Method



    public function update_profiles_social(Request $request)
    {
        $sql = AdminSettings::find(1);



        $rules = array(

            'twitter'    => 'url',

            'facebook'   => 'url',

            'googleplus' => 'url',

            'linkedin'   => 'url',

        );



        $this->validate($request, $rules);



        $sql->twitter       = $request->twitter;

        $sql->facebook      = $request->facebook;

        $sql->googleplus    = $request->googleplus;

        $sql->instagram     = $request->instagram;



        $sql->save();



        \Session::flash('success_message', trans('admin.success_update'));



        return redirect('panel/admin/profiles-social');
    }//<--- End Method

    public function amils()
    {
      $data = Amils::orderBy('id', 'DESC')->paginate(100);

      return view('admin.amils', ['data' => $data, 'settings' => $this->settings]);
    }

    public function addAmils()
    {
      return view('admin/add-amil');
    }

    public function storeAmils(Request $request)
    {
        $this->validate($request, [
            'branch' => 'required|integer',
            'gender' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'provinsi' => 'required',
            'kecamatan' => 'required',
            'kota' => 'required',
            'kelurahan' => 'required',
            'phone' => 'required',
            'amil_type' => 'required',
            'home_type' => 'required',
            'name' => 'required|min:3|max:30',
            'email'     => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Save User first
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'amil';
        $user->avatar = 'default.jpg';
        $user->token = str_random(80);
        $user->password = \Hash::make($request->password);
        $user->save();

        // Save Amil next
        $amil = new Amils;
        $amil->user_id = $user->id;
        $amil->cabang_id = $request->branch;
        $amil->name = $request->name;
        $amil->gender = $request->gender;
        $amil->weight = $request->weight;
        $amil->height = $request->height;
        $amil->birthplace = $request->birthplace;
        $amil->birthdate = date('Y-m-d', strtotime($request->birthdate));
        $amil->address = $request->address;
        $amil->postalcode = $request->postalcode;
        $amil->provinsi = $request->provinsi;
        $amil->kota = $request->kota;
        $amil->kecamatan = $request->kecamatan;
        $amil->kelurahan = $request->kelurahan;
        $amil->phonenumber = $request->phone;
        $amil->email = $request->email;
        $amil->amil_type = $request->amil_type;
        $amil->home_type = $request->home_type;
        $amil->save();

        // Intiate Amil location
        $location = new Locations;
        $location->amil_id = $amil->id;
        $location->status = 'off';
        $location->save();

        \Session::flash('success_message', trans('admin.success_add'));
        return redirect('panel/admin/amils');
    }

    public function bank()
    {
        $data = Banks::orderBy('id', 'ASC')->paginate(100);

        return view('admin.banks', ['data' => $data, 'settings' => $this->settings]);
    }

    public function addBank()
    {
        $bank = new Banks;
        $action = route('admin-bank-store');
        $title  = trans('misc.add_new');
        return view('admin.form-bank', compact('bank', 'action', 'title'));
    }

    public function storeBank(Request $request)
    {
      $this->validate($request, [
          'name' => 'required',
          'branch' => 'required',
          'account_number' => 'required',
      ]);

      // Save User first
      $bank = new Banks;
      $temp = 'public/temp/';
      $path_small = 'public/bank/small/';
      $path_large = 'public/bank/large/';

      if ($request->hasFile('image')) {
          $extension    = $request->file('image')->getClientOriginalExtension();
          $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
          $file_small     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
    
          if ($request->file('image')->move($temp, $file_large)) {
              set_time_limit(0);
    
              //=============== Image Large =================//
              $width  = Helper::getWidth($temp.$file_large);
              $height = Helper::getHeight($temp.$file_large);
              // $max_width = '800';
    
              // if ($width < $height) {
              //     $max_width = '400';
              // }
    
              // if ($width > $max_width) {
              //     $scale = $max_width / $width;
              //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
              // } else {
              //     $scale = 1;
              //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
              // }
    
              //=============== Small Large =================//
              // Helper::resizeImageFixed($temp.$file_large, 400, 300, $temp.$file_small);
    
              //======= Copy Folder Small and Delete...
              if (\File::exists($temp.$file_small)) {
                  \File::copy($temp.$file_small, $path_small.$file_small);
                  \File::delete($temp.$file_small);
              }//<--- IF FILE EXISTS
    
              Image::make($temp.$file_large)->orientate();
    
              //======= Copy Folder Large and Delete...
              if (\File::exists($temp.$file_large)) {
                  \File::copy($temp.$file_large, $path_large.$file_large);
                  \File::delete($temp.$file_large);
              }//<--- IF FILE EXISTS
          }
    
          $image_small  = $file_small;
          $image_large  = $file_large;
      }//<====== End HasFile

      $bank->logo = $image_large;
      $bank->account_name = $request->name;
      $bank->name = $request->branch;
      $bank->account_number = $request->account_number;
      $bank->save();

      \Session::flash('success_message', trans('admin.success_add'));
      return redirect('panel/admin/settings/bank');
    }

    public function editBank(Request $request, $id)
    {
        if (!$bank = Banks::find($id)) {
            return redirect()->back()->with('error_message', 'Bank not found');
        }
        $action = route('admin-bank-update', $id);
        $title  = 'Edit Bank';
        return view('admin.form-bank', compact('bank', 'action', 'title'));
    }

    public function updateBank(Request $request)
    {
      $this->validate($request, [
          'name' => 'required',
          'branch' => 'required',        
          'account_number' => 'required',
      ]);

      // Save User first
      $bank = new Banks;
      $temp = 'public/temp/';
      $path_small = 'public/bank/small/';
      $path_large = 'public/bank/large/';

      if ($request->hasFile('image')) {
          $extension    = $request->file('image')->getClientOriginalExtension();
          $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
          $file_small     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
    
          if ($request->file('image')->move($temp, $file_large)) {
              set_time_limit(0);
    
              //=============== Image Large =================//
              $width  = Helper::getWidth($temp.$file_large);
              $height = Helper::getHeight($temp.$file_large);
              // $max_width = '800';
    
              // if ($width < $height) {
              //     $max_width = '400';
              // }
    
              // if ($width > $max_width) {
              //     $scale = $max_width / $width;
              //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
              // } else {
              //     $scale = 1;
              //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
              // }
    
              //=============== Small Large =================//
              // Helper::resizeImageFixed($temp.$file_large, 400, 300, $temp.$file_small);
    
              //======= Copy Folder Small and Delete...
              if (\File::exists($temp.$file_small)) {
                  \File::copy($temp.$file_small, $path_small.$file_small);
                  \File::delete($temp.$file_small);
              }//<--- IF FILE EXISTS
    
              Image::make($temp.$file_large)->orientate();
    
              //======= Copy Folder Large and Delete...
              if (\File::exists($temp.$file_large)) {
                  \File::copy($temp.$file_large, $path_large.$file_large);
                  \File::delete($temp.$file_large);
              }//<--- IF FILE EXISTS
          }
    
          $image_small  = $file_small;
          $image_large  = $file_large;
      }//<====== End HasFile

      $bank->logo = $image_large;
      $bank->account_name = $request->name;
      $bank->name = $request->branch;
      $bank->account_number = $request->account_number;
      $bank->save();

      \Session::flash('success_message', trans('admin.success_update'));
      return redirect('panel/admin/settings/bank');
    }

    public function donations()
    {
        $data = Donations::orderBy('id', 'DESC')->paginate(100);
        $data->map(function ($d){
          $bank = Banks::where('id', '=', $d->bank_id)->first();
          $d['bank'] = $bank;
          return $d;
        });

        return view('admin.donations', ['data' => $data, 'settings' => $this->settings]);
    }//<--- End Method



    public function donationView($id)
    {
        $data = Donations::findOrFail($id);
        $logs = DonationsLog::where('donations_id','=',$id)->first();
        if(!is_null($logs)) {
          $banks = Banks::where('id', '=', $logs->bank_id)->first();
          $data['logs'] = $logs;
          $data['banks'] = $banks;
        }

        return view('admin.donation-view', ['data' => $data, 'settings' => $this->settings]);
    }//<--- End Method

    public function donationAccept($id)
    {
        $data = Donations::findOrFail($id);
        if ($data->payment_status != 'paid') {
            $data->payment_status = 'paid';
            $data->save();
        }

        $campaign = Campaigns::findOrFail($data->campaigns_id);
        $percentage = $campaign->affiliator_bonus_percentage;

        $request = new Request();
        $value = $request->cookie('refDonation');
        $refDonation = ReferralDonasi::find($value);
        if(isset($refDonation)) {
            $refDonation->status = 'add';
            $refDonation->bonus = ($percentage/100) * $data->donation;
            $refDonation->save();
        }

        return redirect('panel/admin/donations/'.$id);
    }

    public function donationReject($id)
    {
        $data = Donations::findOrFail($id);
        $data->payment_status = 'denied';
        $data->save();

        return redirect('panel/admin/donations/'.$id);
    }

    public function payments()
    {
        return view('admin.payments-settings')->withSettings($this->settings);
    }//<--- End Method



    public function savePayments(Request $request)
    {
        $sql = AdminSettings::find(1);



        $rules = array(

            'paypal_account'    => 'required|email',

        );



        $this->validate($request, $rules);



        switch ($request->currency_code) {

            case 'USD':

                $currency_symbol  = '$';

                break;

            case 'EUR':

                $currency_symbol  = '€';

                break;

            case 'GBP':

                $currency_symbol  = '£';

                break;

            case 'AUD':

                $currency_symbol  = '$';

                break;

            case 'JPY':

                $currency_symbol  = '¥';

                break;



            case 'BRL':

                $currency_symbol  = 'R$';

                break;

            case 'MXN':

                $currency_symbol  = '$';

                break;

            case 'SEK':

                $currency_symbol  = 'Kr';

                break;

            case 'CHF':

                $currency_symbol  = 'CHF';

                break;

            case 'SGD':

                $currency_symbol  = '$';

                break;

            case 'DKK':

                $currency_symbol  = 'Kr';

                break;

            case 'RUB':

                $currency_symbol  = 'руб';

                break;

            case 'CAD':

                $currency_symbol  = '$';

                break;

            case 'CZK':

                $currency_symbol  = 'Kč';

                break;

            case 'HKD':

                $currency_symbol  = 'HK$';

                break;

            case 'PLN':

                $currency_symbol  = 'zł';

                break;

            case 'NOK':

                $currency_symbol  = 'Kr';

                break;

        }



        $sql->paypal_account     = $request->paypal_account;

        $sql->currency_symbol  = $currency_symbol;

        $sql->currency_code    = $request->currency_code;

        $sql->paypal_sandbox   = $request->paypal_sandbox;

        $sql->payment_gateway    = $request->payment_gateway;

        $sql->fee_donation = $request->fee_donation;

        $sql->stripe_secret_key    = $request->stripe_secret_key;

        $sql->stripe_public_key    = $request->stripe_public_key;



        $sql->save();



        \Session::flash('success_message', trans('admin.success_update'));



        return redirect('panel/admin/payments');
    }//<--- End Method



    public function campaigns()
    {
        $_data = Campaigns::orderBy('id', 'DESC')->paginate(50);



        // Deadline

        $timeNow = strtotime(Carbon::now());



        foreach ($_data as $key) {
            if ($key->deadline != '') {
                $_deadline = strtotime($key->deadline);

                // if ($_deadline < $timeNow && $key->finalized == '0') {
                //     $sql = Campaigns::find($key->id);
                //     $sql->finalized = '1';
                //     $sql->save();
                // }
            }
        }



        $data = Campaigns::orderBy('id', 'DESC')->paginate(50);

        return view('admin.campaigns', ['data' => $data, 'settings' => $this->settings]);
    }//<--- End Method



    public function withdrawals()
    {
        $data = Withdrawals::orderBy('id', 'DESC')->paginate(50);

        return view('admin.withdrawals', ['data' => $data, 'settings' => $this->settings]);
    }//<--- End Method



    public function withdrawalsView($id)
    {
        $data = Withdrawals::findOrFail($id);

        return view('admin.withdrawal-view', ['data' => $data, 'settings' => $this->settings]);
    }//<--- End Method



    public function withdrawalsPaid(Request $request)
    {
        $data = Withdrawals::findOrFail($request->id);

        $user = User::find($data->campaigns()->user_id);



        $data->status       = 'paid';

        $data->date_paid = Carbon::now();

        $data->save();



        //<------ Send Email to User ---------->>>

        $amount    = $this->settings->currency_symbol.$data->amount.' '.$this->settings->currency_code;

        $sender     = $this->settings->email_no_reply;

        $titleSite     = $this->settings->title;

        $campaign = $data->campaigns()->title;

        $fullNameUser = $user->name;

        $_emailUser = $user->email;



        Mail::send(


            'emails.withdrawal-processed',


            array(

                    'campaign' => $campaign,

                    'amount' => $amount,

                    'title_site' => $titleSite,

                    'fullname' => $fullNameUser

        ),

                    function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
                        $message->from($sender, $titleSite)

                                ->to($_emailUser, $fullNameUser)

                                ->subject(trans('misc.withdrawal_processed').' - '.$titleSite);
                    }


        );

        //<------ Send Email to User ---------->>>



        return redirect('panel/admin/withdrawals');
    }//<--- End Method



    public function withdrawlsIpn()
    {
        $ipn = new PaypalIPNListener();



        $ipn->use_curl = false;



        if ($this->settings->paypal_sandbox == 'true') {

            // SandBox

            $ipn->use_sandbox = true;
        } else {

            // Real environment

            $ipn->use_sandbox = false;
        }



        $verified = $ipn->processIpn();



        $withdrawalsID    = $_POST['custom'];

        $payment_status = $_POST['payment_status'];

        $txn_id               = $_POST['txn_id'];



        if ($verified) {
            if ($payment_status == 'Completed') {
                $verifiedTxnId = Withdrawals::where('txn_id', $txn_id)->first();

                $data             = Withdrawals::find($withdrawalsID);

                $user            = User::find($data->campaigns()->user_id);



                if (!isset($verifiedTxnId) && isset($data)) {
                    $data->status       = 'paid';

                    $data->date_paid = Carbon::now();

                    $data->save();



                    //<------ Send Email to User ---------->>>

                    $amount    = $this->settings->currency_symbol.$data->amount.' '.$this->settings->currency_code;

                    $sender     = $this->settings->email_no_reply;

                    $titleSite     = $this->settings->title;

                    $campaign = $data->campaigns()->title;

                    $fullNameUser = $user->name;

                    $_emailUser = $user->email;



                    Mail::send(


            'emails.withdrawal-processed',


            array(

                    'campaign' => $campaign,

                    'amount' => $amount,

                    'title_site' => $titleSite,

                    'fullname' => $fullNameUser

        ),

                    function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
                        $message->from($sender, $titleSite)

                                ->to($_emailUser, $fullNameUser)

                                ->subject(trans('misc.withdrawal_processed').' - '.$titleSite);
                    }


        );

                    //<------ Send Email to User ---------->>>
                }// Isset $verifiedTxnId
            }// $payment_status
        }// $verified
    }//<--- End Method





    public function editCampaigns($id)
    {
        $data = Campaigns::findOrFail($id);

        return view('admin.edit-campaign', ['data' => $data, 'settings' => $this->settings]);
    }



    public function postEditCampaigns(Request $request)
    {
        $sql = Campaigns::findOrFail($request->id);



        Validator::extend('text_required', function ($attribute, $value, $parameters) {
            $value = preg_replace("/\s|&nbsp;/", '', $value);

            return strip_tags($value);
        });



        $messages = array(

        'description.required' => trans('misc.description_required'),

        'description.text_required' => trans('misc.text_required'),

        'categories_id.required' => trans('misc.please_select_category'),

        'goal.min' => trans('misc.amount_minimum', ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

        'goal.max' => trans('misc.amount_maximum', ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

    );



        $rules = array(

            'title'             => 'required|min:3|max:45',

            'categories_id'  => 'required',

            'goal'             => 'required|integer|min:'.$this->settings->min_campaign_amount,

             'location'        => 'required|max:50',

            'description'  => 'text_required|required|min:20',

            'affiliation_bonus' => 'required'
        );



        $this->validate($request, $rules, $messages);



        $description = html_entity_decode($request->description);



        //REMOVE SCRIPT TAG

        $description = Helper::removeTagScript($description);



        //REMOVE SCRIPT Iframe

        //$description = Helper::removeTagIframe($description);



        $description = trim(Helper::spaces($description));



        // Status

        if ($request->finalized == 'pending') {
            $statusGlobal = 'pending';

            $request->finalized = '0';
        } elseif ($request->finalized == '0' || $request->finalized == 1) {
            $statusGlobal = 'active';

            $request->finalized = $request->finalized;
        }


        $city = Kabupaten::where('id_kab', '=', $request->location)->first();

        $sql->title = $request->title;

        $sql->goal = $request->goal;

        $sql->location          = $city->nama;

        $sql->province_id = $city->id_prov;

        $sql->city_id = $city->id_kab;

        $sql->description = Helper::removeBR($description);

        $sql->finalized = $request->finalized;

        $sql->status = $statusGlobal;

        $sql->categories_id = $request->categories_id;

        $sql->featured = $request->featured;

        $sql->akun_transaksi_id = $request->akun_transaksi_id;

        $sql->cabang_id = $request->cabang_id;

        $sql->affiliator_bonus_percentage = $request->affiliation_bonus;

        $sql->save();

        KategoriCampaign::bulkEdit($sql->id, $request->input('kategori', []));

        \Session::flash('success_message', trans('admin.success_update'));

        return redirect('panel/admin/campaigns');
    }



    public function deleteCampaign(Request $request)
    {
        $data = Campaigns::findOrFail($request->id);



        $path_small     = 'public/campaigns/small/';

        $path_large     = 'public/campaigns/large/';

        $path_updates = 'public/campaigns/updates/';



        $updates = $data->updates()->get();



        //Delete Updates

        foreach ($updates as $key) {
            if (\File::exists($path_updates.$key->image)) {
                \File::delete($path_updates.$key->image);
            }//<--- if file exists



            $key->delete();
        }//<--



        // Delete Campaign

        if (\File::exists($path_small.$data->small_image)) {
            \File::delete($path_small.$data->small_image);
        }//<--- if file exists



        if (\File::exists($path_large.$data->large_image)) {
            \File::delete($path_large.$data->large_image);
        }//<--- if file exists



        $data->delete();



        \Session::flash('success_message', trans('misc.success_delete'));

        return redirect('panel/admin/campaigns');
    }



    // START

    public function categories()
    {
        $categories      = Categories::orderBy('name')->where('slug', '!=', 'public')->get();

        $totalCategories = count($categories);



        return view('admin.categories', compact('categories', 'totalCategories'));
    }//<--- END METHOD



    public function addCategories()
    {
        return view('admin.add-categories');
    }//<--- END METHOD



    public function storeCategories(Request $request)
    {
        $temp            = 'public/temp/'; // Temp
        $path            = 'public/img-category/'; // Path General

        Validator::extend('ascii_only', function ($attribute, $value, $parameters) {
            return !preg_match('/[^x00-x7F\-]/i', $value);
        });

        $rules = array(
            'name'        => 'required',
            'slug'        => 'required|ascii_only|unique:categories',
            'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|image_size:>=457,>=359',
        );

        $this->validate($request, $rules);

        if ($request->hasFile('thumbnail')) {
            $extension              = $request->file('thumbnail')->getClientOriginalExtension();
            $type_mime_shot   = $request->file('thumbnail')->getMimeType();
            $sizeFile                 = $request->file('thumbnail')->getSize();
            $thumbnail              = $request->slug.'-'.str_random(32).'.'.$extension;

            if ($request->file('thumbnail')->move($temp, $thumbnail)) {
                $image = Image::make($temp.$thumbnail);

                if ($image->width() == 457 && $image->height() == 359) {
                    \File::copy($temp.$thumbnail, $path.$thumbnail);
                    \File::delete($temp.$thumbnail);
                } else {
                    $image->fit(457, 359)->save($temp.$thumbnail);
                    \File::copy($temp.$thumbnail, $path.$thumbnail);
                    \File::delete($temp.$thumbnail);
                }
            }// End File
        } // HasFile



        else {
            $thumbnail = '';
        }

        $sql                      = new Categories;
        $sql->name         = $request->name;
        $sql->slug            = $request->slug;
        $sql->mode         = $request->mode;
        $sql->image       = $thumbnail;
        $sql->save();

        \Session::flash('success_message', trans('admin.success_add_category'));
        return redirect('panel/admin/categories');
    }//<--- END METHOD



    public function editCategories($id)
    {
        $categories = Categories::find($id);

        return view('admin.edit-categories')->with('categories', $categories);
    }//<--- END METHOD



    public function updateCategories(Request $request)
    {
        $categories = Categories::find($request->id);
        $temp = 'public/temp/'; // Temp
        $path = 'public/img-category/'; // Path General

        if (!isset($categories)) {
            return redirect('panel/admin/categories');
        }

        Validator::extend('ascii_only', function ($attribute, $value, $parameters) {
            return !preg_match('/[^x00-x7F\-]/i', $value);
        });

        $rules = array(
            'name'        => 'required',
            'slug'        => 'required|ascii_only|unique:categories,slug,'.$request->id,
            'thumbnail'   => 'mimes:jpg,gif,png,jpe,jpeg|image_size:>=457,>=359',
         );
        $this->validate($request, $rules);

        if ($request->hasFile('thumbnail')) {
            $extension = $request->file('thumbnail')->getClientOriginalExtension();
            $type_mime_shot = $request->file('thumbnail')->getMimeType();
            $sizeFile = $request->file('thumbnail')->getSize();
            $thumbnail = $request->slug.'-'.str_random(32).'.'.$extension;

            if ($request->file('thumbnail')->move($temp, $thumbnail)) {
                $image = Image::make($temp.$thumbnail);

                if ($image->width() == 457 && $image->height() == 359) {
                    \File::copy($temp.$thumbnail, $path.$thumbnail);
                    \File::delete($temp.$thumbnail);
                } else {
                    $image->fit(457, 359)->save($temp.$thumbnail);
                    \File::copy($temp.$thumbnail, $path.$thumbnail);
                    \File::delete($temp.$thumbnail);
                }

                // Delete Old Image
                \File::delete($path.$categories->thumbnail);
            }// End File
        } else {
            // HasFile
            $thumbnail = $categories->image;
        }

        // UPDATE CATEGORY
        $categories->name = $request->name;
        $categories->slug = $request->slug;
        $categories->mode = $request->mode;
        $categories->image = $thumbnail;
        $categories->save();

        \Session::flash('success_message', trans('misc.success_update'));

        return redirect('panel/admin/categories');
    }//<--- END METHOD



    public function deleteCategories($id)
    {
        $categories = Categories::find($id);
        $thumbnail = 'public/img-category/'.$categories->image; // Path General

        if (!isset($categories)) {
            return redirect('panel/admin/categories');
        } else {
            // Delete Category
            $categories->delete();

            // Delete Thumbnail
            if (\File::exists($thumbnail)) {
                \File::delete($thumbnail);
            }//<--- IF FILE EXISTS

            return redirect('panel/admin/categories');
        }
    }//<--- END METHOD



    public function reportedCampaigns()
    {
        $data = CampaignsReported::orderBy('id', 'DESC')->paginate(50);
        return view('admin.reported-campaigns')->withData($data);
    }//<--- END METHOD



    public function reportedDeleteCampaigns(Request $request)
    {
        $report = CampaignsReported::find($request->id);

        if (isset($report)) {
            $report->delete();
            return redirect('panel/admin/campaigns/reported');
        } else {
            return redirect('panel/admin/campaigns/reported');
        }
    }//<--- END METHOD
}// End Class
