<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Categories;
use App\Models\Donations;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Updates;
use App\Helper;
use App\Models\Like;

use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */

    public function campaigns()
    {
        $settings = AdminSettings::first();
        $data = Campaigns::where('status', 'active')->orderBy('id', 'DESC')->paginate($settings->result_request);
        $data->map(function ($d){
          $d['provinsi'] = Provinsi::where('id_prov', '=', $d->province_id)->first();
          $d['kabupaten'] = Kabupaten::where('id_kab', '=', $d->city_id)->first();
          return $d;
        });

        return view('ajax.campaigns', ['data' => $data, 'settings' => $settings])->render();
    }

    public function updatesCampaign()
    {
        $settings = AdminSettings::first();
        $page     = $this->request->input('page');
        $id         = $this->request->input('id');
        $data     = Updates::where('campaigns_id', $id)->orderBy('id', 'desc')->paginate(1);
        $data->map(function ($d){
          $d['provinsi'] = Provinsi::where('id_prov', '=', $d->province_id)->first();
          $d['kabupaten'] = Kabupaten::where('id_kab', '=', $d->city_id)->first();
          return $d;
        });

        return view('ajax.updates-campaign', ['data' => $data, 'settings' => $settings])->render();
    }

    public function search()
    {
        $settings = AdminSettings::first();

        $q = $this->request->slug;

        $data = Campaigns::where('title', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->orWhere('location', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->groupBy('id')
        ->orderBy('id', 'desc')
        ->paginate($settings->result_request);

        return view('ajax.campaigns', ['data' => $data, 'settings' => $settings, 'slug' => $q])->render();
    }

    public function like()
    {
        $like = Like::firstOrNew(['user_id' => Auth::user()->id, 'campaigns_id' => $this->request->id]);

        $campaign = Campaigns::find($this->request->id);

        if ($like->exists) {

                // IF ACTIVE DELETE Like
            if ($like->status == '1') {
                $like->status = '0';
                $like->update();

            // ELSE ACTIVE AGAIN
            } else {
                $like->status = '1';
                $like->update();
            }
        } else {

            // INSERT
            $like->save();
        }
        $totalLike = Helper::formatNumber($campaign->likes()->count());

        return $totalLike;
    }

    // Category

    public function category()
    {
        $settings = AdminSettings::first();

        $slug = $this->request->slug;

        $category = Categories::where('slug', '=', $slug)->first();
        $data = Campaigns::where('status', 'active')->where('categories_id', $category->id)->orderBy('id', 'DESC')->paginate($settings->result_request);
        $data->map(function ($d){
          $d['provinsi'] = Provinsi::where('id_prov', '=', $d->province_id)->first();
          $d['kabupaten'] = Kabupaten::where('id_kab', '=', $d->city_id)->first();
          return $d;
        });

        return view('ajax.campaigns', ['data' => $data, 'settings' => $settings, 'slug' => $category->slug])->render();
    }

    // Donations

    public function donations()
    {
        $settings = AdminSettings::first();
        $page   = $this->request->input('page');
        $id        = $this->request->input('id');
        $data    = Donations::where('campaigns_id', $id)->orderBy('id', 'desc')->paginate(10);

        return view('ajax.donations', ['data' => $data, 'settings' => $settings])->render();
    }
}
