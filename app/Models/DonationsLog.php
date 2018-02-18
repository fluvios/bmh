<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationsLog extends Model
{
    protected $guarded = array();
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User')->first();
    }

    public function donation()
    {
        return $this->belongsTo('App\Models\Donation')->first();
    }
}
