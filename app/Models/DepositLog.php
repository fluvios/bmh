<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Events\TopupSuccess;

class DepositLog extends Model
{
    protected $guarded = array();
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User')->first();
    }

    public static function accept($id)
    {
    	$data = self::findOrFail($id);
        if ($data && $data->payment_status != 'paid') {
        	$data->payment_status = 'paid';
        	$data->save();

        	$user = User::findOrFail($data->user_id);
        	$now_saldo = $user->saldo + $data->amount;
        	$user->saldo = $now_saldo;
        	$user->save();
        }
    }

    public static function boot()
    {
        parent::boot();

        // static::updated(function($item) {
        //     if ($item->payment_status == 'paid') {
        //         event(new TopupSuccess($item, $item->user()));
        //     }
        // });

        static::saved(function($item) {
            if ($item->payment_status == 'paid') {
                event(new TopupSuccess($item, $item->user()));
            }
        });
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

    public function getExpiry()
    {
        return \Carbon\Carbon::parse(self::find($this->id)->expired_date)->format('d M H:i');
    }
}
