<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amils extends Model
{
    protected $guarded = array();
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User')->first();
    }

    public function educations()
    {
        return $this->hasMany('App\Models\Educations');
    }

    public function locations()
    {
        return $this->hasMany('App\Models\Locations');
    }

    public function families()
    {
        return $this->hasMany('App\Models\Families');
    }

    public function positions()
    {
        return $this->hasMany('App\Models\Locations');
    }
}
