<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';

    public function campaigns() {
        return $this->belongsTo('App\Models\Campaigns', 'cabang_id'); 
  }
}
