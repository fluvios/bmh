<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    public function kategoriCampaign() {
		return $this->hasMany('App\Models\KategoriCampaign', 'id', 'kategori_id');
	}
}
