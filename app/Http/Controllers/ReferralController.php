<?php

namespace App\Http\Controllers;

use App\Models\ReferralDonasi;
use App\Models\ReferralRegistrasi;
use App\Models\User;
use App\Models\Donations;
use App\Models\Campaigns;
use App\Models\AdminSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReferralController extends Controller
{
  public function __construct(AdminSettings $settings)
  {
      $this->settings = $settings::first();
  }

  public function refferalDonasi($email, $id)
  {
      $users = User::where('email', $email)->first();
      $donation = Donations::where('id', $id)->first();
      $campaign = Campaigns::where('id', $donation->campaigns_id)->first();

    if (str_slug($campaign->title) == '') {
        $slugUrl  = '';
    } else {
        $slugUrl  = '/'.str_slug($campaign->title);
    }

    $url_campaign = $campaign->id.$slugUrl;
      
      $referralDonasi = new ReferralDonasi();
      $referralDonasi->link_donasi = url('donate/'.$url_campaign);
      $referralDonasi->email = $users->email;
      $referralDonasi->status = 'hold';
      $referralDonasi->bonus = 0;

      $referralDonasi->save();

      return redirect('donate/'.$url_campaign)->withCookie(cookie('refDonation', $referralDonasi->id, 1));
  }

  public function refferalRegistrasi($email)
  {
      $users = User::where('email', $email)->first();

      $referralRegistrasi = new ReferralRegistrasi();
      $referralRegistrasi->email = $users->email;
      $referralRegistrasi->status = 'add';
      $referralRegistrasi->bonus = $this->settings->registration_bonus;
      $referralRegistrasi->save();

      return redirect('login')->withCookie(cookie('refRegistrasi', $referralRegistrasi->id, 1));
  }
}
