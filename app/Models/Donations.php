<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Events\DonationSuccess;

class Donations extends Model
{
    protected $guarded = array();
    public $timestamps = false;

    public function campaigns()
    {
        return $this->belongsTo('App\Models\Campaigns')->first();
    }

	public function donationslog()
	{
        return $this->hasMany('App\Models\DonationsLog')->first();
	}

    public function getExpiry()
    {
        return \Carbon\Carbon::parse(self::find($this->id)->expired_date)->format('d M H:i');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getPaymentMethod()
    {
        if ($bank = Banks::find($this->bank_id)) {
            return 'Transfer - '.$bank->slug;
        } elseif ($this->payment_gateway == 'Midtrans') {
            return 'Midtrans';
        } else {
            return 'Pembayaran Lain';
        }
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function($donation) {
            if ($donation->payment_status == 'paid') {
                event(new DonationSuccess($donation, $donation->user));
            }
        });
    }
}
