<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Categories;
use App\Models\Donations;
use App\Models\DepositLog;
use App\Models\Updates;
use App\Models\Kabupaten;
use App\Helper;
use App\Models\Like;
use App\Models\Cabang;
use App\Models\Kategori;
use App\Models\KategoriCampaign;
use App\Models\AkunTransaksi;
use App\Models\Magazines;
use Carbon\Carbon;

class APIController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // Campaign
    public function campaigns()
    {
        $settings = AdminSettings::first();
        $campaigns = Campaigns::where('status', 'active')
        ->orderBy('id', 'DESC')->paginate($settings->result_request);
        $campaigns->map(function ($campaign){
          $donations = Donations::where('campaigns_id', '=', $campaign->id)->where('payment_status', '=', 'paid')->get();
          $updates = Updates::where('campaigns_id', '=', $campaign->id)->orderBy('id','desc')->get();
          $total = Donations::where('campaigns_id', '=', $campaign->id)->where('payment_status', '=', 'paid')->sum('donation');
          
          $timeNow = strtotime(Carbon::now());

          if( $campaign->deadline != '' ) {
              $deadline = strtotime($campaign->deadline);
      
              $date = strtotime($campaign->deadline);
              $remaining = $date - $timeNow;
      
              $days_remaining = floor($remaining / 86400);
          }

          if (str_slug($campaign->title) == '') {
            $slugUrl  = '';
          } else {
            $slugUrl  = str_slug($campaign->title);
          }

          $campaign['donation'] = $donations;
          $campaign['update'] = $updates;
          $campaign['total'] = $total;
          $campaign['days_remaining'] = $days_remaining;
          $campaign['slug'] = $slugUrl;
          $campaign['kategori'] = KategoriCampaign::where('campaign_id', $campaign->id)->get();

          return $campaign;
        });

        return $campaigns;
    }

    public function campaignDetail($id, $slug = null)
    {
        return Campaigns::where('id', $id)->where('status', 'active')->firstOrFail();
    }

    public function updatesCampaigns()
    {
        $settings = AdminSettings::first();
        $page = $this->request->input('page');
        $id = $this->request->input('id');
        return Updates::where('campaigns_id', $id)->orderBy('id', 'desc')->paginate(1);
    }

    public function search()
    {
        $settings = AdminSettings::first();
        $q = $this->request->slug;
        return Campaigns::where('title', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->orWhere('location', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->groupBy('id')
        ->orderBy('id', 'desc')
        ->paginate($settings->result_request);
    }

    public function category()
    {
        $settings = AdminSettings::first();

        $slug = $this->request->slug;

        $category = Categories::where('slug', '=', $slug)->first();
        return Campaigns::where('status', 'active')->where('categories_id', $category->id)->orderBy('id', 'DESC')->paginate($settings->result_request);
    }

    public function donations()
    {
        return $data = Donations::orderBy('id', 'DESC')->paginate(100);
    }//<--- End Method
    
    public function magazines()
    {
        return Magazines::orderBy('id', 'DESC')->paginate(100);
    }

    public function filter()
    {
        $data = [];
        $data['kategori'] = Kategori::all();
        $data['jenis-dana'] = Categories::all();
        $data['kota'] = Kabupaten::all();

        return $data;
    }

    public function cabang()
    {
        $term = $this->request->input('term');
        $page = $this->request->input('page',1);
        $result =  Cabang::where('nama', 'like', '%'.$term.'%')->orWhere('kode', 'like', '%'.$term.'%')->paginate(10, ['*'],'page', $page);
        $result->getCollection()->transform(function($data) {
            return [
                'id'   => $data->id,
                'text' => $data->nama
            ];
        });
        return $result;
    }

    public function kategori()
    {
        $term = $this->request->input('term');
        $page = $this->request->input('page',1);
        $result =  Kategori::where('is_active', 1)->where('nama', 'like', '%'.$term.'%')->paginate(10, ['*'],'page', $page);
        $result->getCollection()->transform(function($data) {
            return [
                'id'   => $data->id,
                'text' => $data->nama
            ];
        });
        return $result;
    }

    public function akunTransaksi()
    {
        $term = $this->request->input('term');
        $page = $this->request->input('page',1);
        $result =  AkunTransaksi::where('nama', 'like', '%'.$term.'%')->orWhere('nomor', 'like', '%'.$term.'%')->paginate(10, ['*'],'page', $page);
        $result->getCollection()->transform(function($data) {
            return [
                'id'   => $data->id,
                'text' => $data->nomor.' - '.$data->nama
            ];
        });
        return $result;
    }
}
