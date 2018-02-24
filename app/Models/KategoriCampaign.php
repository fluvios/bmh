<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriCampaign extends Model
{
    protected $table = 'kategori_campaign';
    public $timestamps = false;

    public function campaign() 
    {
    	return $this->belongsTo('App\Models\Campaign', 'campaign_id', 'id');
    }

    public function kategori() 
    {
    	return $this->belongsTo('App\Models\Kategori', 'kategori_id', 'id');
    }

    public static function bulkAdd($campaignId, $kategoriIds=[])
    {
    	foreach ($kategoriIds as $kategoriId) {
    		if (!$category = Kategori::find($kategoriId)) {
    			continue;
    		}
    		$model = new self();
    		$model->campaign_id = $campaignId;
    		$model->kategori_id = $kategoriId;
    		$model->save();
    	}

    }

    public static function bulkEdit($campaignId, $kategoriIds=[])
    {
    	if ($kategoriIds) {
	    	self::where('campaign_id', $campaignId)->delete();
	    	self::bulkAdd($campaignId, $kategoriIds);
    	}
    }

    public static function getSelectedOption($campaignId)
    {
    	$models = self::with('kategori')->where('campaign_id', $campaignId)->get();
    	$option = '';
    	foreach ($models as $key => $model) {
    		$name = $model->kategori->nama;
    		$kategoriId = $model->kategori_id;
    		$option .= "<option value='{$kategoriId}' selected> {$name} </option>";
    	}
    	return $option;
    }

    public static function getSelectedArray($campaignId)
    {
    	$ids = self::with('kategori')->where('campaign_id', $campaignId)->get()->pluck('kategori_id');
    	if ($ids->count() >= 1) {
    		return '['. $ids->implode(',') .']';
    	} else {
    		return '[]';
    	}

    }
}
