<?php

namespace App\Http\Controllers;

use App\Models\ReferralDonasi;
use App\Models\ReferralRegistrasi;
use App\Models\User;
use App\Models\Donations;
use App\Models\AdminSettings;

use Illuminate\Http\Request;

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
      $campaign = Campaigns::where('id', $donation->campaign_id)->first();

      $referralDonasi = new ReferralDonasi();
      $referralDonasi->link_donasi = url('donate/'.$campaign->id.$campaign->slug);
      $referralDonasi->email = $users->email;
      $referralDonasi->bonus = $this->settings->donation_bonus;

      $referralDonasi->save();

      redirect('donate/'.$campaign->id.$campaign->slug);
  }

  public function refferalRegistrasi($email)
  {
      $users = User::where('email', $email)->first();

      $referralRegistrasi = new ReferralRegistrasi();
      $referralRegistrasi->email = $users->email;
      $referralRegistrasi->bonus = $this->settings->registration_bonus;

      $referralRegistrasi->save();

      redirect('register');
  }
}
