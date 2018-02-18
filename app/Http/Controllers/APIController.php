<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Categories;
use App\Models\Donations;
use App\Models\Updates;
use App\Helper;
use App\Models\Like;

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
          $total = Donations::where('campaigns_id', '=', $campaign->id)->where('payment_status', '=', 'paid')->sum('donation');
          $campaign['donation'] = $donations;
          $campaign['total'] = $total;
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
}
