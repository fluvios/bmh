<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\DonationsLog;
use App\Models\User;
use App\Models\Banks;
use App\Models\DepositLog;
use Fahim\PaypalIPN\PaypalIPNListener;
use App\Helper;
use Mail;
use Carbon\Carbon;
use DB;
use App\Events\NewBankTransfer;
use App\Includes\Veritrans\Veritrans_VtWeb;
use App\Includes\Veritrans\Veritrans_Snap;
use App\Includes\Veritrans\Veritrans_Notification;

class DonationsController extends Controller
{
  public function __construct(AdminSettings $settings, Request $request)
  {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  /**
  *
  * @return \Illuminate\Http\Response
  */
  public function show($id, $slug = null)
  {
    $response = Campaigns::where('id', $id)->where('status', 'active')->firstOrFail();

    $timeNow = strtotime(Carbon::now());

    if ($response->deadline != '') {
      $deadline = strtotime($response->deadline);
    }

    // Redirect if campaign is ended
    if (!isset($deadline) && $response->finalized == 1) {
      return redirect('campaign/'.$response->id);
    } elseif (isset($deadline) && $deadline < $timeNow) {
      return redirect('campaign/'.$response->id);
    }

    $uriCampaign = $this->request->path();

    if (str_slug($response->title) == '') {
      $slugUrl  = '';
    } else {
      $slugUrl  = '/'.str_slug($response->title);
    }

    $url_campaign = 'donate/'.$response->id.$slugUrl;

    //<<<-- * Redirect the user real page * -->>>
    $uriCanonical = $url_campaign;

    if ($uriCampaign != $uriCanonical) {
      return redirect($uriCanonical);
    }

    return view('default.donate')->withResponse($response);
  }// End Method

  public function send()
  {
    $messages = array(
      'amount.min' => trans('misc.amount_minimum', ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'amount.max' => trans('misc.amount_maximum', ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
    );

    $campaign = Campaigns::findOrFail($this->request->_id);
    $amountKey = Helper::randomCheckKey();

    $validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer|min:'.$this->settings->min_donation_amount.'|max:'.$this->settings->max_donation_amount,
      'full_name' => 'required|max:25',
      'email' => 'required|max:100',
      'comment' => 'max:100',
      'payment_gateway' => 'required',
      'donation_type' => 'required',
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }
    
    //<----------- ****** PAYPAL ************** ----->
    if ($this->request->payment_gateway == 'Paypal') {
      if ($this->settings->paypal_sandbox == 'true') {
        // SandBox
        $action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
      } else {
        // Real environment
        $action = "https://www.paypal.com/cgi-bin/webscr";
      }

      $urlSuccess = url('paypal/donation/success', $campaign->id);
      $urlCancel = url('paypal/donation/cancel', $campaign->id);
      $urlPaypalIPN = url('paypal/ipn');

      return response()->json([
        'success' => true,
        'formPP' => '<form id="form_pp" name="_xclick" action="'.$action.'" method="post"  style="display:none">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="return" value="'.$urlSuccess.'">
        <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
        <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
        <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
        <input type="hidden" name="amount" id="amount" value="'.$this->request->amount.'">
        <input type="hidden" name="custom" value="id='.$campaign->id.'&fn='.$this->request->full_name.'&mail='.$this->request->email.'&cc='.$this->request->country.'&pc='.$this->request->postal_code.'&cm='.$this->request->comment.'&anonymous='.$this->request->anonymous.'">
        <input type="hidden" name="item_name" value="'.trans('misc.donation_for').' '.$campaign->title.'">
        <input type="hidden" name="business" value="'.$this->settings->paypal_account.'">
        <input type="submit">
        </form>',
      ]);
    }

    //<----------- ****** PAYPAL ************** ----->

    //<----------- ****** STRIPE ************** ----->
    elseif ($this->request->payment_gateway == 'Stripe') {
      $email = $this->request->email;
      $cents = bcmul($this->request->amount, 100);
      $amount = (int)$cents;
      $currency_code = $this->settings->currency_code;
      $description = trans('misc.donation_for').' '.$campaign->title;
      $nameSite = $this->settings->title;


      if (isset($this->request->stripeToken)) {
        \Stripe\Stripe::setApiKey($this->settings->stripe_secret_key);


        // Get the credit card details submitted by the form
        $token = $this->request->stripeToken;

        // Create a charge: this will charge the user's card
        try {
          $charge = \Stripe\Charge::create(array(
            "amount" => $amount, // Amount in cents
            "currency" => strtolower($currency_code),
            "source" => $token,
            "description" => $description
          ));

          if (!isset($this->request->anonymous)) {
            $this->request->anonymous = '0';
          }

          // Insert DB and send Mail
          $sql = new Donations;
          $sql->campaigns_id = $campaign->id;
          $sql->txn_id = 'null';
          $sql->fullname = $this->request->full_name;
          $sql->email = $this->request->email;
          $sql->country = $this->request->country;
          $sql->postal_code = $this->request->postal_code;
          $sql->donation = $this->request->amount;
          $sql->payment_gateway = 'Stripe';
          $sql->comment = $this->request->comment;
          $sql->anonymous = $this->request->anonymous;
          $sql->save();

          $sender = $this->settings->email_no_reply;
          $titleSite = $this->settings->title;
          $_emailUser = $this->request->email;
          $campaignID = $campaign->id;
          $fullNameUser = $this->request->fullname;

          Mail::send(
            'emails.thanks-donor',
            array( 'data' => $campaignID, 'fullname' => $fullNameUser, 'title_site' => $titleSite ),
            function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
              $message->from($sender, $titleSite)
              ->to($_emailUser, $fullNameUser)
              ->subject(trans('misc.thanks_donation').' - '.$titleSite);
            }
          );

          return response()->json([
            'success' => true,
            'stripeSuccess' => true,
            'url' => url('paypal/donation/success', $campaign->id)
          ]);
        } catch (\Stripe\Error\Card $e) {
          // The card has been declined
        }
      } else {
        return response()->json([
          'success' => true,
          'stripeTrue' => true,
          "key" => $this->settings->stripe_public_key,
          "email" => $email,
          "amount" => $amount,
          "currency" => strtoupper($currency_code),
          "description" => $description,
          "name" => $nameSite
        ]);
      }
    }
    //<----------- ****** STRIPE ************** ----->

    //<----------- ****** DEPOSIT ************** ----->
    elseif ($this->request->payment_gateway == 'Deposit') {
      $saldo = Auth::user()->saldo - $this->request->amount;
      if ($saldo > 0) {
        if (!isset($this->request->anonymous)) {
          $this->request->anonymous = '0';
        }

        // Insert DB
        $sql = new Donations;
        $sql->campaigns_id = $campaign->id;
        $sql->user_id = Auth::user()->id;
        $sql->txn_id = 'null';
        $sql->fullname = $this->request->full_name;
        $sql->email = $this->request->email;
        $sql->donation = $this->request->amount;
        $sql->donation_type = $this->request->donation_type;
        $sql->payment_gateway = $this->request->payment_gateway;
        $sql->comment = $this->request->comment;
        $sql->payment_status = 'paid';
        $sql->anonymous = $this->request->anonymous;
        $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
        $sql->save();

        // Get Donation Id
        $response = $sql->id;

        // Update Saldo
        User::where('id', Auth::user()->id)->update(array( 'saldo' => $saldo ));

        return response()->json([
          'success' => true,
          'stripeSuccess' => true,
          'url' => url('deposit', $response)
        ]);


        $sender = $this->settings->email_no_reply;
        $titleSite = $this->settings->title;
        $_emailUser = $this->request->email;
        $campaignID = $campaign->id;
        $fullNameUser = $this->request->fullname;

        // Mail::send(
        //   'emails.thanks-donor',
        //   array( 'data' => $campaignID, 'fullname' => $fullNameUser, 'title_site' => $titleSite ),
        //   function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
        //     $message->from($sender, $titleSite)
        //     ->to($_emailUser, $fullNameUser)
        //     ->subject(trans('misc.thanks_donation').' - '.$titleSite);
        //   }
        // );

      } else {
        return response()->json([
          'success' => false,
          'message' => 'Saldo anda tidak mencukupi',
        ]);
      }
    }
    //<----------- ****** DEPOSIT ************** ----->

    
    //<----------- ****** MIDTRANS ************** ----->
    elseif ($this->request->payment_gateway == 'Midtrans') {
      if (!isset($this->request->anonymous)) {
        $this->request->anonymous = '0';
      }

      // Insert DB
      $sql = new Donations;
      $sql->campaigns_id = $campaign->id;
      $sql->user_id = Auth::user()->id;
      $sql->txn_id = 'null';
      $sql->fullname = $this->request->full_name;
      $sql->email = $this->request->email;
      $sql->donation = $this->request->amount;
      $sql->donation_type = $this->request->donation_type;
      $sql->payment_gateway = "Midtrans";
      $sql->comment = $this->request->comment;
      $sql->amount_key = 0;
      $sql->expired_date = \Carbon\Carbon::now()->addDay();
      $sql->anonymous = $this->request->anonymous;
      $sql->save();

      // Get Donation Id
      $response = $sql->id;
      $url      = '';
      $token    = ''; 
      try {
        $params = [
          'transaction_details' => [
            'order_id' => 'campaign-'.$sql->id,
            'gross_amount' => $sql->donation,
          ],
          'item_details'  => [[
            'id'          => 'campaign-' . $sql->campaigns_id,
            'price'       => $sql->donation,
            'quantity'    => 1,
            'name'        => "Donasi Campaign ". $sql->campaigns_id,
          ]],
          'customer_details' => [
            'first_name' => $this->request->full_name,
            'last_name' => '',
            'email'      => $this->request->email,
          ],
          'vtweb' => []
        ];

        if (isset($this->request->is_mobile) && $this->request->is_mobile == 1) {
          $token = Veritrans_Snap::getSnapToken($params);
        } else {
          $url = Veritrans_VtWeb::getRedirectionUrl($params);
        }
      } catch (\Exception $e) {

      }

      // Redirect to transfer page
      return response()->json([
        'success' => true,
        'stripeSuccess' => true,
        'data' => $response,
        'url' => $url,
        'token' => $token,
      ]);
    
    //<----------- ****** MIDTRANS ************** ----->
    //<----------- ****** TRANSFER ************** ----->   
    } else {
      if (!isset($this->request->anonymous)) {
        $this->request->anonymous = '0';
      }

      // Insert DB
      $sql = new Donations;
      $sql->campaigns_id = $campaign->id;
      $sql->user_id = Auth::user()->id;
      $sql->txn_id = 'null';
      $sql->fullname = $this->request->full_name;
      $sql->email = $this->request->email;
      $sql->donation = $this->request->amount + $amountKey;
      $sql->donation_type = $this->request->donation_type;
      $sql->payment_gateway = "Transfer";
      $sql->comment = $this->request->comment;
      $sql->bank_id = $this->request->payment_gateway;
      $sql->amount_key = $amountKey;
      $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
      $sql->anonymous = $this->request->anonymous;
      $sql->save();

      // Get Donation Id
      $response = $sql->id;

      // $sender = $this->settings->email_no_reply;
      // $titleSite = $this->settings->title;
      // $_emailUser = $this->request->email;
      // $campaignID = $campaign->id;
      // $fullNameUser = $this->request->fullname;

      // Mail::send(
      //   'emails.thanks-donor',
      //   array( 'data' => $campaignID, 'fullname' => $fullNameUser, 'title_site' => $titleSite ),
      //   function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
      //     $message->from($sender, $titleSite)
      //     ->to($_emailUser, $fullNameUser)
      //     ->subject(trans('misc.thanks_donation').' - '.$titleSite);
      //   }
      // );
      
      if ($bank = Banks::find($sql->bank_id)) {
        event(new NewBankTransfer($sql, Auth::user(), $bank));
      }

      // Redirect to transfer page
      return response()->json([
        'success' => true,
        'stripeSuccess' => true,
        'data' => $response,
        'url' => url('transfer', $response)
      ]);
    }
    //<----------- ****** TRANSFER ************** ----->
  }// End Method

  public function sendMobile()
  {
    $validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer',
      'full_name' => 'required|max:25',
      'email' => 'required|max:100',
      'comment' => 'max:100',
      'payment_gateway' => 'required',
      'donation_type' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    $amountKey = Helper::randomCheckKey();

    //<----------- ****** DELIVERY ************** ----->
    if ($this->request->payment_gateway == 'Delivery') {
      if (!isset($this->request->anonymous)) {
        $this->request->anonymous = '0';
      }

      // Insert DB
      $sql = new Donations;
      $sql->campaigns_id = $this->request->campaign_id;
      $sql->user_id = $this->request->user_id;
      $sql->txn_id = 'null';
      $sql->fullname = $this->request->full_name;
      $sql->email = $this->request->email;
      $sql->donation = $this->request->amount + $amountKey;
      $sql->donation_type = $this->request->donation_type;
      $sql->payment_gateway = $this->request->payment_gateway;
      $sql->comment = $this->request->comment;
      $sql->anonymous = $this->request->anonymous;
      $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
      $sql->save();

      // Get Donation Data
      $response = $sql;
            
      return response()->json([
        'donation' => $response,
        'success' => true,
        'message' => 'Donasi berhasil tersimpan'
      ]);
    }
    //<----------- ****** DELIVERY ************** ----->

    //<----------- ****** DEPOSIT ************** ----->
    elseif ($this->request->payment_gateway == 'Deposit') {
      $user = User::where('id', '=', $this->request->user_id)->firstOrFail();
      if ($user->saldo > 0 && $user->saldo > $this->request->amount) {
        # code...
        if (!isset($this->request->anonymous)) {
          $this->request->anonymous = '0';
        }

        // Insert DB
        $sql = new Donations;
        $sql->campaigns_id = $this->request->campaign_id;
        $sql->user_id = $this->request->user_id;
        $sql->txn_id = 'null';
        $sql->fullname = $this->request->full_name;
        $sql->email = $this->request->email;
        $sql->donation = $this->request->amount;
        $sql->donation_type = $this->request->donation_type;
        $sql->payment_gateway = $this->request->payment_gateway;
        $sql->comment = $this->request->comment;
        $sql->payment_status = 'paid';
        $sql->anonymous = $this->request->anonymous;
        $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
        $sql->save();

        // Update Saldo
        $saldo = $user->saldo - $this->request->amount;
        User::where('id', $this->request->user_id)->update(array( 'saldo' => $saldo ));
        
        // Get Donation Data
        $response = $sql;
              
        return response()->json([
          'donation' => $response,
          'success' => true,
          'message' => 'Donasi berhasil tersimpan'
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Saldo anda tidak mencukupi',
        ]);
      }
    }
    //<----------- ****** DEPOSIT ************** ----->

        //<----------- ****** MIDTRANS ************** ----->
    elseif ($this->request->payment_gateway == 'Midtrans') {
      if (!isset($this->request->anonymous)) {
        $this->request->anonymous = '0';
      }

      // Insert DB
      $sql = new Donations;
      $sql->campaigns_id = $this->request->campaign_id;
      $sql->user_id = $this->request->user_id;
      $sql->txn_id = 'null';
      $sql->fullname = $this->request->full_name;
      $sql->email = $this->request->email;
      $sql->donation = $this->request->amount;
      $sql->donation_type = $this->request->donation_type;
      $sql->payment_gateway = "Midtrans";
      $sql->comment = $this->request->comment;
      $sql->amount_key = 0;
      $sql->expired_date = \Carbon\Carbon::now()->addDay();
      $sql->anonymous = $this->request->anonymous;
      $sql->save();

      // Get Donation Id
      $response = $sql->id;
      $url      = '';
      $token    = ''; 
      try {
        $params = [
          'transaction_details' => [
            'order_id' => 'campaign-'.$sql->id,
            'gross_amount' => $sql->donation,
          ],
          'item_details'  => [[
            'id'          => 'campaign-' . $sql->campaigns_id,
            'price'       => $sql->donation,
            'quantity'    => 1,
            'name'        => "Donasi Campaign ". $sql->campaigns_id,
          ]],
          'customer_details' => [
            'first_name' => $this->request->full_name,
            'last_name' => '',
            'email'      => $this->request->email,
          ],
          'vtweb' => []
        ];

        if (isset($this->request->is_mobile) && $this->request->is_mobile == 1) {
          $token = Veritrans_Snap::getSnapToken($params);
        } else {
          $url = Veritrans_VtWeb::getRedirectionUrl($params);
        }
      } catch (\Exception $e) {

      }

      // Redirect to transfer page
      return response()->json([
        'success' => true,
        'stripeSuccess' => true,
        'data' => $response,
        'url' => $url,
        'token' => $token,
      ]);
    
    //<----------- ****** MIDTRANS ************** ----->
    //<----------- ****** TRANSFER ************** ----->   
    } else {
      if (!isset($this->request->anonymous)) {
        $this->request->anonymous = '0';
      }

      // Insert DB
      $sql = new Donations;
      $sql->campaigns_id = $this->request->campaign_id;
      $sql->user_id = $this->request->user_id;
      $sql->txn_id = 'null';
      $sql->fullname = $this->request->full_name;
      $sql->email = $this->request->email;
      $sql->donation = $this->request->amount + $amountKey;
      $sql->donation_type = $this->request->donation_type;
      $sql->payment_gateway = 'Transfer';
      $sql->comment = $this->request->comment;
      $sql->anonymous = $this->request->anonymous;
      $sql->bank_id = $this->request->payment_gateway;
      $sql->amount_key = $amountKey;
      $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
      $sql->save();

      // Get Donation Data
      $response = Donations::find($sql->id);
      $response['bank'] = Banks::findOrFail($sql->bank_id);

      return response()->json([
        'donation' => $response,
        'success' => true,
        'message' => 'Donasi berhasil tersimpan'
      ]);
    }
    //<----------- ****** TRANSFER ************** ----->
  }// End Method

  public function transfer($id)
  {
    // $settings = AdminSettings::first();
    //
    // $validator = Validator::make($this->request->all(), [
    //   'bank_id' => 'required',
    //   'photo' => 'required|mimes:jpg,gif,png,jpe,jpeg|image_size:>=125,>=125|max:'.$settings->file_size_allowed.'',
    // ]);
    //
    // if ($validator->fails()) {
    //   return response()->json([
    //     'success' => false,
    //     'errors' => $validator->getMessageBag()->toArray(),
    //   ]);
    // }

    // PATHS
    // $temp    = 'public/temp/';
    // $path    = 'public/transfer/';

    //<--- HASFILE PHOTO
    // if ($this->request->hasFile('photo')) {
    //   $extension  = $this->request->file('photo')->getClientOriginalExtension();
    //   $avatar = strtolower(Auth::user()->id.time().str_random(15).'.'.$extension);
    //
    //   if ($this->request->file('photo')->move($temp, $avatar)) {
    //     set_time_limit(0);

        // Copy folder
        // if (\File::exists($temp.$avatar)) {
          /* Avatar */
          // \File::copy($temp.$avatar, $path.$avatar);
          // \File::delete($temp.$avatar);
        // }
        //<--- IF FILE EXISTS

        // Insert Database
        $donation = Donations::where('id', '=', $id)->firstOrFail();
        // $sql = new DonationsLog;
        // $sql->donations_id = $id;
        // $sql->user_id = Auth::user()->id;
        // $sql->bank_id = $this->request->bank_id;
        // $sql->amount = $donation->donation;
        // $sql->payment_gateway = $donation->payment_gateway;
        // $sql->transfer_evidance = $avatar;
        // $sql->save();

        // update payment status
        // Donations::where('id', $this->request->_id)->update(array( 'payment_status' => 'paid' ));

        return response()->json([
          'success' => true,
          'stripeSuccess' => true,
          'url' => url('paypal/donation/success', $donation->campaigns_id)
        ]);
    //   }
    // }
  }

  public function transferMobile($id)
  {
    $settings = AdminSettings::first();

    $validator = Validator::make($this->request->all(), [
      'user_id' => 'required',
      'photo' => 'required|mimes:jpg,gif,png,jpe,jpeg|image_size:>=125,>=125|max:'.$settings->file_size_allowed.'',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    // PATHS
    $temp    = 'public/temp/';
    $path    = 'public/transfer/';

    //<--- HASFILE PHOTO
    if ($this->request->hasFile('photo')) {
      $extension  = $this->request->file('photo')->getClientOriginalExtension();
      $avatar = strtolower($this->request->user_id.time().str_random(15).'.'.$extension);

      if ($this->request->file('photo')->move($temp, $avatar)) {
        set_time_limit(0);

        // Copy folder
        if (\File::exists($temp.$avatar)) {
          /* Avatar */
          \File::copy($temp.$avatar, $path.$avatar);
          \File::delete($temp.$avatar);
        }//<--- IF FILE EXISTS

        // Insert Database
        $donation = Donations::where('id', '=', $id)->firstOrFail();
        $sql = new DonationsLog;
        $sql->donations_id = $id;
        $sql->user_id = $this->request->user_id;
        $sql->bank_id = $this->request->bank_id;
        $sql->amount = $donation->donation;
        $sql->payment_gateway = $donation->payment_gateway;
        $sql->transfer_evidance = $avatar;
        $sql->save();

        // update payment status
        // Donations::where('id', $id)->update(array( 'payment_status' => 'paid' ));

        return response()->json([
          'success' => true,
          'stripeSuccess' => true,
          'status' => 'Donasi sukses'
        ]);
      }
    }
  }

  public function midtransIpn()
  {
    $ipn = new Veritrans_Notification();

    $transaction = $ipn->transaction_status;
    $type = $ipn->payment_type;
    $order_id = $ipn->order_id;
    $fraud = $ipn->fraud_status;


    if ($transaction == 'capture' || $transaction == 'settlement') {
      if (preg_match('/(.*)-(\d+)/', $order_id, $matches)) {
        $orderType = $matches[1];
        $order_id  = $matches[2];
        switch ($orderType) {
          case 'campaign':
            // 
            $donation = Donations::find($order_id);
            if ($donation->payment_status != 'paid') {
              $donation->payment_status = 'paid';
              $donation->payment_date   = \Carbon\Carbon::now();
              $donation->save(); 
            }
            break;
          case 'topup':
            DepositLog::accept($order_id);
            break;
          default:
            # code...
            break;
        }
      }
    }

    return response()->json(['status' => 'ok']);
  }

  public function paypalIpn()
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

    //$report = Helper::checkTextDb($ipn->getTextReport()); // Report the transation

    $custom  = $_POST['custom'];
    parse_str($custom, $donation);

    $payment_status = $_POST['payment_status'];
    $txn_id               = $_POST['txn_id'];
    $amount             = $_POST['mc_gross'];


    if ($verified) {
      if ($payment_status == 'Completed') {
        // Check outh POST variable and insert in DB

        $verifiedTxnId = Donations::where('txn_id', $txn_id)->first();

        if (!isset($verifiedTxnId)) {
          $sql = new Donations;
          $sql->campaigns_id = $donation['id'];
          $sql->txn_id = $txn_id;
          $sql->fullname = $donation['fn'];
          $sql->email = $donation['mail'];
          $sql->country = $donation['cc'];
          $sql->postal_code = $donation['pc'];
          $sql->donation = $amount;
          $sql->payment_gateway = 'Paypal';
          $sql->comment = $donation['cm'];
          $sql->anonymous = $donation['anonymous'];
          $sql->save();

          $sender = $this->settings->email_no_reply;
          $titleSite = $this->settings->title;
          $_emailUser    = $donation['mail'];
          $campaignID   = $donation['id'];
          $fullNameUser = $donation['fn'];

          Mail::send(

            'emails.thanks-donor',

            array( 'data' => $campaignID, 'fullname' => $fullNameUser, 'title_site' => $titleSite ),
            function ($message) use ($sender, $fullNameUser, $titleSite, $_emailUser) {
              $message->from($sender, $titleSite)
              ->to($_emailUser, $fullNameUser)
              ->subject(trans('misc.thanks_donation').' - '.$titleSite);
            }

          );
        }// <--- Verified Txn ID
      } // <-- Payment status
    } else {
      //Some thing went wrong in the payment !
    }
  }// End Method

  public function donationAccept($id)
  {
    $data = Donations::findOrFail($id);
    $data->payment_status = 'paid';
    $data->save();

    return redirect('panel/admin/donation');
  }

  public function donationReject($id)
  {
    $data = Donations::findOrFail($id);
    $data->payment_status = 'denied';
    $data->save();
    return redirect('panel/admin/donation');
  }

  public function emailTest($id)
  {
    $sql = Donations::find($id);
    if ($bank = Banks::find($sql->bank_id)) {
      event(new NewBankTransfer($sql, User::find($sql->user_id), $bank));
    }
  }
}
