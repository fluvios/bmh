<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'type', 'born_date', 'phone_number_1', 'phone_number_2', 'name',
      'password', 'email', 'avatar', 'status','role','token',
      'confirmation_code','countries_id', 'saldo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaigns');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Countries', 'countries_id')->first();
    }

    public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function amils()
    {
        return $this->hasMany('App\Models\Amils');
    }

    public function donations()
    {
        return $this->hasMany('App\Models\Donations', 'user_id', 'id');
    }

    public function donationMade()
    {
        return $this->donations->sum('donation');
    }
}
