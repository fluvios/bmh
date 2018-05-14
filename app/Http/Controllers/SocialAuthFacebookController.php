<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use App\Models\SocialMedia;
use App\Models\User;
use Socialite;

class SocialAuthFacebookController extends Controller
{
  /**
   * Create a redirect method to facebook api.
   *
   * @return void
   */
  public function redirect($service)
  {
      return Socialite::driver( $service )->redirect();
  }

  /**
   * Return a callback method from facebook api.
   *
   * @return callback URL from facebook
   */
  public function callback($service)
  {
    $user = Socialite::driver( $service )->user();

    $socialUser = null;

    //Check is this email present
    $userCheck = User::where('email', '=', $user->email)->first();

    $email = $user->email;

    if (!$user->email) {
        $email = 'missing' . str_random(10);
    }

    if (!empty($userCheck)) {

        $socialUser = $userCheck;

    }
    else {

        $sameSocialId = SocialMedia::where('social_id', '=', $user->id)
            ->where('provider', '=', $service )
            ->first();

        if (empty($sameSocialId)) {

            //There is no combination of this social id and provider, so create new one
            $newSocialUser = new User;
            $newSocialUser->email = $email;
            $name = explode(' ', $user->name);

            if (count($name) >= 1) {
                $newSocialUser->first_name = $name[0];
            }

            if (count($name) >= 2) {
                $newSocialUser->last_name = $name[1];
            }

            $newSocialUser->name = $user->name;
            $newSocialUser->password = bcrypt(str_random(16));
            $newSocialUser->token = str_random(64);
            $newSocialUser->save();

            $socialData = new SocialMedia;
            $socialData->social_id = $user->id;
            $socialData->provider= $service;
            $newSocialUser->socialmedia()->save($socialData);
        }
        else {
            //Load this existing social user
            $socialUser = $sameSocialId->user;
        }
    }

    auth()->login($socialUser, true);
    if ( auth()->user()) {
        return redirect()->intended('/');
    }
  }
}
