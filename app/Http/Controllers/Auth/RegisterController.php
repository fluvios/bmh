<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;
use App\Includes\apifunction;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
    * Where to redirect users after login / registration.
    *
    * @var string
    */
    protected $redirectTo = '/';

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
      $messages = array(
        'countries_id.required' => trans('misc.please_select_country'),
      );

      return Validator::make($data, [
        'user_id' => '',
        'datebirth' => '',
        'phone1' => 'required',
        'phone2' => '',
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
        'countries_id'     => '',
      ], $messages);
    }


    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return User
    */
    protected function create(array $data)
    {
      $settings = AdminSettings::first();

      // Verify Settings Admin
      if ($settings->email_verification == 1) {
        $confirmation_code = str_random(100);
        $status = 'pending';

        //send verification mail to user
        $_username      = $data['name'];
        $_email_user    = $data['email'];
        $_title_site    = $settings->title;
        $_email_noreply = $settings->email_no_reply;

        Mail::send(
          'emails.verify',
          array('confirmation_code' => $confirmation_code, 'title_site' => $_title_site ),
          function ($message) use (
            $_username,
            $_email_user,
            $_title_site,
            $_email_noreply
          ) {
            $message->from($_email_noreply, $_title_site);
            $message->subject(trans('users.title_email_verify'));
            $message->to($_email_user, $_username);
          }
        );

        if(Mail::failures()) {
          echo "There is a failure in email send";
        }

        // Send sms
         $phone1 = apifunction::sendsms($data['phone1'], "BerbagiKebaikan.org - Silakan Cek Email Anda untuk Verifikasi Akun Anda");
        // $phone2 = apifunction::sendsms($data['phone2'], "Verify your account now");
      } else {
        $confirmation_code = '';
        $status            = 'active';
      }

      $token = str_random(75);

      return User::create([
        'user_id' => $data['user_id'],
        'born_date' => '',
        'phone_number_1' => $data['phone1'],
        'phone_number_2' => '',
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'countries_id' => 'Indonesia',
        'avatar' => 'default.jpg',
        'status' => $status,
        'role' => 'normal',
        'token' => $token,
        'confirmation_code' => $confirmation_code
      ]);
    }

    protected function createFacebook(Request $request)
    {
      $token = str_random(75);

      $user = new User();
      $user->user_id = $request->user_id;
      $user->born_date = '';
      $user->phone_number_1 = '';
      $user->phone_number_2 = '';
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = '';
      $user->countries_id = 'Indonesia';
      $user->avatar = 'default.jpg';
      $user->status = 'active';
      $user->role = 'normal';
      $user->token = $request->idToken;
      $user->confirmation_code = '';
      $user->save();
      
      $temp = User::where('user_id', '=', $request->user_id)->first();
      $temp['login-type'] = 'facebook';

      return response()->json([
        'success' => true,
        'user' => $temp
      ]);
    }
}
