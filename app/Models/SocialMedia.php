<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'social_media';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
