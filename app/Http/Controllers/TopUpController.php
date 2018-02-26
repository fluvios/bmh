<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\User;
use App\Models\DepositLog;
use App\Models\Banks;
use App\Helper;
use App\Includes\Veritrans\Veritrans_VtWeb;
use App\Includes\Veritrans\Veritrans_Snap;
use DB;
use App\Events\NewBankTopupTransfer;

class TopUpController extends Controller
{
  public function __construct(AdminSettings $settings, Request $request)
  {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  public function sendMobile()
  {
    $validator = Validator::make($this->request->all(), [
      'amount' => 'required',
      'payment_gateway' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    $user = User::where('id', '=',  $this->request->user_id)->firstOrFail();
    $amountKey = Helper::randomCheckKey();

    //<----------- ****** DELIVERY ************** ----->
    if ($this->request->payment_gateway == 'Delivery') {
      // Insert DB
      // Insert DB
      $sql = new DepositLog;
      $sql->user_id = $this->request->user_id;
      $sql->fullname = $user->name;
      $sql->email = $user->email;
      $sql->amount = $this->request->amount;
      $sql->payment_gateway = $this->request->payment_gateway;
      $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
      $sql->save();

      // Get Topup
      $response = DepositLog::find($sql->id);

      return response()->json([
        'success' => true,
        'deposit' => $response,
        'message' => 'Topup sukses',
      ]);
    }
    //<----------- ****** DELIVERY ************** ----->

    //<----------- ****** TRANSFER ************** ----->
    else{
      // Insert DB
      $sql = new DepositLog;
      $sql->user_id = $this->request->user_id;
      $sql->bank_id = $this->request->payment_gateway;
      $sql->amount = $this->request->amount + $amountKey;
      $sql->fullname = $user->name;
      $sql->email = $user->email;
      $sql->payment_gateway = 'Transfer';
      $sql->amount_key = $amountKey;
      $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
      $sql->save();

      // Get Topup
      $response = DepositLog::find($sql->id);
      $response['bank'] = Banks::findOrFail($sql->bank_id);

      // Redirect to transfer page
      return response()->json([
        'success' => true,
        'deposit' => $response,
        'message' => 'Topup sukses',
      ]);
    }
    //<----------- ****** TRANSFER ************** ----->
  }// End Method

  public function transferMobile($id)
  {
    $settings = AdminSettings::first();

    $validator = Validator::make($this->request->all(), [
      'bank_id' => 'required',
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
    $path    = 'public/topup/';

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
        $sql = DepositLog::where('id', '=', $id)->firstOrFail();
        $sql->bank_id = $this->request->bank_id;
        $sql->transfer_evidance = $avatar;
        $sql->save();

        // update payment status
        // Donations::where('id', $id)->update(array( 'payment_status' => 'paid' ));

        return response()->json([
          'success' => true,
          'stripeSuccess' => true,
          'status' => 'Topup sukses'
        ]);
      }
    }
  }

  public function send()
  {
    $validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer',
      'payment_gateway' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }


    $isMidtrans = $this->request->payment_gateway == 'Midtrans';

    $amountKey = $isMidtrans ? 0 : Helper::randomCheckKey();
    
    //<----------- ****** TRANSFER ************** ----->
    // Insert DB
    $sql = new DepositLog;
    $sql->user_id = Auth::user()->id;
    $sql->bank_id = $isMidtrans ? 0 : $this->request->payment_gateway;
    $sql->amount = $this->request->amount + $amountKey;
    $sql->fullname = Auth::user()->name;
    $sql->email = Auth::user()->email;
    $sql->payment_gateway = $isMidtrans ? 'Midtrans' : 'Transfer';
    $sql->amount_key = $amountKey;
    $sql->expired_date = DB::raw('NOW() + INTERVAL 1 DAY');
    $sql->save();

    // Get Donation Id
    $response = $sql->id;
    $url = url('transfer_topup', $response);
    $token = '';
    if ($isMidtrans) {
      try {
        $params = [
          'transaction_details' => [
            'order_id' => 'topup-'.$sql->id,
            'gross_amount' => $sql->amount,
          ],
          'item_details' => [[
            'id' => 'topup-' . $sql->id,
            'price' => $sql->amount,
            'quantity' => 1,
            'name' => "Topup",
          ]],
          'customer_details' => [
            'first_name' => Auth::user()->name,
            'last_name' => '',
            'email'      => Auth::user()->email,
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
    }

    if ($bank = Banks::find($sql->bank_id)) {
      event(new NewBankTopupTransfer($sql, Auth::user(), $bank));
    }

    // Redirect to transfer page
    return response()->json([
      'success' => true,
      'stripeSuccess' => true,
      'url' => $url,
      'token' => $token,
    ]);
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
    // $path    = 'public/topup/';

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
        //   \File::copy($temp.$avatar, $path.$avatar);
        //   \File::delete($temp.$avatar);
        // }
        //<--- IF FILE EXISTS

        // Insert Database

        // $deposit = DepositLog::where('id', '=', $id)->firstOrFail();
        // DepositLog::where('id', $this->request->_id)->update(
        //   array(
        //     'bank_id' => $this->request->bank_id,
        //     'transfer_evidance' =>$avatar
        //   )
        // );

        // update payment status
        // Donations::where('id', $this->request->_id)->update(array( 'payment_status' => 'paid' ));

        return response()->json([
          'success' => true,
          'stripeSuccess' => true,
          'url' => url('account/topup')
        ]);
    //   }
    // }
  }

  public function showdata()
  {
    $data = DepositLog::orderBy('id', 'DESC')->paginate(100);

    return view('admin.topups', ['data' => $data, 'settings' => $this->settings]);
  }

  public function topupview($id)
  {
    $data = DepositLog::findOrFail($id);
    $logs = DepositLog::where('id', '=', $id)->first();
    if (!is_null($logs)) {
      $banks = Banks::where('id', '=', $logs->bank_id)->first();
      $data['logs'] = $logs;
      $data['banks'] = $banks;
    }
    return view('admin.topup-view', ['data' => $data, 'settings' => $this->settings]);
  }//<--- End Method

  public function topupAccept($id)
  {
    $data = DepositLog::findOrFail($id);
    $data->payment_status = 'paid';
    $data->save();

    $user = User::findOrFail($data->user_id);
    $now_saldo = $user->saldo + $data->amount;
    $user->saldo = $now_saldo;
    $user->save();

    return redirect('panel/admin/top_up/'.$id);
  }

  public function topupReject($id)
  {
    $data = DepositLog::findOrFail($id);
    $data->payment_status = 'denied';
    $data->save();
    return redirect('panel/admin/top_up/'.$id);
  }
}
