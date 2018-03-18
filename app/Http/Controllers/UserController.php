<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Address;
use App\Helper;
use Carbon\Carbon;

class UserController extends Controller
{
  protected function validator(array $data, $id = null)
  {
    Validator::extend('ascii_only', function ($attribute, $value, $parameters) {
      return !preg_match('/[^x00-x7F\-]/i', $value);
    });

    // Validate if have one letter
    Validator::extend('letters', function ($attribute, $value, $parameters) {
      return preg_match('/[a-zA-Z0-9]/', $value);
    });

    $messages = array(
      'countries_id.required' => trans('misc.please_select_country'),
    );

    return Validator::make($data, [
      'full_name' => 'required|min:3|max:25',
      'email'     => 'required|email|unique:users,email,'.$id,
      'countries_id'     => 'required',
    ], $messages);
  }//<--- End Method

  public function dashboard()
  {
    $user = Auth::user();
    return view('users.dashboard', [
      'user' => $user,
    ]);
  }//<--- End Method

  public function account()
  {
    return view('users.account');
  }//<--- End Method

  public function referral()
  {
    return view('users.referral');
  }//<--- End Method

  public function topup()
  {
    $response = Auth::user()->id;
    return view('users.topup')->withResponse($response);
  }

  public function update_account(Request $request)
  {
    $input = $request->all();
    $id = Auth::user()->id;

    $validator = $this->validator($input, $id);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request,
        $validator
      );
    }

    $user = User::find($id);
    $user->name        = $input['full_name'];
    $user->email        = trim($input['email']);
    $user->countries_id = $input['countries_id'];
    $user->phone_number_1 = $input['phone_number_1'];
    $user->phone_number_2 = $input['phone_number_2'];
    $user->save();

    \Session::flash('notification', trans('auth.success_update'));

    return redirect('account');
  }//<--- End Method

  public function updateMobile(Request $request)
  {
    $input = $request->all();

    $validator = $this->validator($input, $id);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request,
        $validator
      );
    }

    $user = User::find($request->user_id);
    $user->name        = $request->name;
    $user->email        = $request->email;
    $user->phone_number_1 = $request->phone_number_1;
    $user->phone_number_2 = $request->phone_number_2;
    $user->save();

    // Send new edit data
    $response = User::find($user->id);

    return response()->json([
      'success' => true,
      'response' => $response,      
    ]);
  }//<--- End Method

  public function password()
  {
    return view('users.password');
  }//<--- End Method

  public function update_adress_home(Request $request)
  {
    $input = $request->all();
    $id = Auth::user()->id;

    $validator = Validator::make($input, [
      'fullhomeaddress' => 'required',
      'homephone' => 'required',
      'homepostalcode' => 'required',
      'homeprovince' => 'required',
      'homestate' => 'required',
      'homeregion' => 'required',
      'homevillage' => 'required'
    ]);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request,
        $validator
      );
    }

    $address = Address::where('user_id', '=', $id)->where('jenis', '=', 'rumah')->firstOrFail();
    $address->alamat = $input['fullhomeaddress'];
    $address->telepon = $input['homephone'];
    $address->kodepos = $input['postalcode'];
    $address->provinsi = $input['homeprovince'];
    $address->Kabupaten = $input['homestate'];
    $address->Kecamatan = $input['homeregion'];
    $address->Kelurahan = $input['homevillage'];
    $address->save();

    return redirect('account');
  }

  public function update_adress_work(Request $request)
  {
    $input = $request->all();
    $id = Auth::user()->id;

    $validator = Validator::make($input, [
      'fullcompanyaddress' => 'required',
      'companyphone' => 'required',
      'companypostalcode' => 'required',
      'companyext' => 'required',
      'companyprovince' => 'required',
      'companystate' => 'required',
      'companyregion' => 'required',
      'companyvillage' => 'required'
    ]);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request,
        $validator
      );
    }

    $address = Address::where('user_id', '=', $id)->where('jenis', '=', 'kantor')->firstOrFail();
    $address->alamat = $input['fullcompanyaddress'];
    $address->telepon = $input['companyphone'];
    $address->kodepos = $input['companypostalcode'];
    $address->ext = $input['companyext'];
    $address->provinsi = $input['companyprovince'];
    $address->Kabupaten = $input['companystate'];
    $address->Kecamatan = $input['companyregion'];
    $address->Kelurahan = $input['companyvillage'];
    $address->save();

    return redirect('account');
  }

  public function update_password(Request $request)
  {
    $input = $request->all();
    $id = Auth::user()->id;

    $validator = Validator::make($input, [
      'old_password' => 'required|min:6',
      'password'     => 'required|min:6',
    ]);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request,
        $validator
      );
    }

    if (!\Hash::check($input['old_password'], Auth::user()->password)) {
      return redirect('account/password')->with(array( 'incorrect_pass' => trans('misc.password_incorrect') ));
    }

    $user = User::find($id);
    $user->password  = \Hash::make($input[ "password"]);
    $user->save();

    \Session::flash('notification', trans('auth.success_update_password'));

    return redirect('account/password');
  }//<--- End Method

  public function delete()
  {
    if (Auth::user()->id == 1) {
      return redirect('account');
    }
    return view('users.delete');
  }//<--- End Method

  public function delete_account()
  {
    if (Auth::user()->id == 1) {
      return redirect('account');
    }

    $id = Auth::user()->id;

    // Find User
    $user = User::find($id);

    // Stop Campaigns
    $allCampaigns = Campaigns::where('user_id', $id)->update(array('finalized' => '1'));

    //<<<-- Delete Avatar -->>>/
    $fileAvatar    = 'public/avatar/'.Auth::user()->avatar;

    if (\File::exists($fileAvatar) && Auth::user()->avatar != 'default.jpg') {
      \File::delete($fileAvatar);
    }//<--- IF FILE EXISTS

    \Session::flush();
    Auth::logout();

    $user->delete();
    return redirect('/');
  }//<--- End Method

  public function upload_avatar(Request $request)
  {
    $settings  = AdminSettings::first();
    $id = Auth::user()->id;

    $validator = Validator::make($request->all(), [
      'photo'       => 'required|mimes:jpg,gif,png,jpe,jpeg|image_size:>=125,>=125|max:'.$settings->file_size_allowed.'',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    // PATHS
    $temp    = 'public/temp/';
    $path    = 'public/avatar/';
    $imgOld      = $path.Auth::user()->avatar;

    //<--- HASFILE PHOTO
    if ($request->hasFile('photo')) {
      $extension  = $request->file('photo')->getClientOriginalExtension();
      $avatar       = strtolower(Auth::user()->id.time().str_random(15).'.'.$extension);

      if ($request->file('photo')->move($temp, $avatar)) {
        set_time_limit(0);

        Helper::resizeImageFixed($temp.$avatar, 125, 125, $temp.$avatar);

        // Copy folder
        if (\File::exists($temp.$avatar)) {
          /* Avatar */
          \File::copy($temp.$avatar, $path.$avatar);
          \File::delete($temp.$avatar);
        }//<--- IF FILE EXISTS

        //<<<-- Delete old image -->>>/
        if (\File::exists($imgOld) && $imgOld != $path.'default.jpg') {
          \File::delete($temp.$avatar);
          \File::delete($imgOld);
        }//<--- IF FILE EXISTS #1

        // Update Database
        User::where('id', Auth::user()->id)->update(array( 'avatar' => $avatar ));

        return response()->json([
          'success' => true,
          'avatar' => url($path.$avatar),
        ]);
      }// Move
    }//<--- HASFILE PHOTO
  }//<--- End Method

  public function likes()
  {
    $data = Campaigns::leftjoin('likes', 'campaigns.id', '=', \DB::raw('likes.campaigns_id AND likes.status = "1"'))
    ->where('campaigns.status', 'active')
    ->where('likes.user_id', '=', Auth::user()->id)
    ->groupBy('likes.id')
    ->orderBy('likes.id', 'desc')
    ->select('campaigns.*')
    ->paginate(12);

    return view('users.likes', ['data' => $data]);
  }//<--- End Method
}
