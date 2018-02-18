<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Campaigns;
use App\Models\Categories;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;

class HomeController extends Controller
{

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = AdminSettings::first();
        $categories = Categories::where('mode', 'on')->orderBy('name')->get();
        $data      = Campaigns::where('status', 'active')->orderBy('id', 'DESC')->paginate($settings->result_request);
        $data->map(function ($d){
          $d['provinsi'] = Provinsi::where('id_prov', '=', $d->province_id)->first();
          $d['kabupaten'] = Kabupaten::where('id_kab', '=', $d->city_id)->first();
          return $d;
        });

        return view('index.home', ['data' => $data, 'categories' => $categories]);
    }

    public function search(Request $request)
    {
        $q = trim($request->input('q'));
        $settings = AdminSettings::first();

        $page = $request->input('page');

        $data = Campaigns::where('title', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->orWhere('location', 'LIKE', '%'.$q.'%')
        ->where('status', 'active')
        ->groupBy('id')
        ->orderBy('id', 'desc')
        ->paginate($settings->result_request);


        $title = trans('misc.result_of').' '. $q .' - ';

        $total = $data->total();

        //<--- * If $page not exists * ---->
        if ($page > $data->lastPage()) {
            abort('404');
        }

        //<--- * If $q is empty or is minus to 1 * ---->
        if ($q == '' || strlen($q) <= 1) {
            return redirect('/');
        }

        return view('default.search', compact('data', 'title', 'total', 'q'));
    }// End Method

    public function getVerifyAccount($confirmation_code)
    {

        // Session Inactive
        if (!Auth::check()) {
            $user = User::where('confirmation_code', $confirmation_code)->where('status', 'pending')->first();

            if ($user) {
                $update = User::where('confirmation_code', $confirmation_code)
            ->where('status', 'pending')
            ->update(array( 'status' => 'active', 'confirmation_code' => '' ));

                Auth::loginUsingId($user->id);

                return redirect('/')
                    ->with([
                        'success_verify' => true,
                    ]);
            } else {
                return redirect('/')
                    ->with([
                        'error_verify' => true,
                    ]);
            }
        } else {

            // Session Active
            $user = User::where('confirmation_code', $confirmation_code)->where('status', 'pending')->first();
            if ($user) {
                $update = User::where('confirmation_code', $confirmation_code)
            ->where('status', 'pending')
            ->update(array( 'status' => 'active', 'confirmation_code' => '' ));

                return redirect('/')
                    ->with([
                        'success_verify' => true,
                    ]);
            } else {
                return redirect('/')
                    ->with([
                        'error_verify' => true,
                    ]);
            }
        }
    }// End Method

    public function category($slug)
    {
        $settings = AdminSettings::first();

        $category = Categories::where('slug', '=', $slug)->where('mode', 'on')->firstOrFail();
        $data       = Campaigns::where('status', 'active')->where('categories_id', $category->id)->orderBy('id', 'DESC')->paginate($settings->result_request);
        $data->map(function ($d){
          $d['provinsi'] = Provinsi::where('id_prov', '=', $d->province_id)->first();
          $d['kabupaten'] = Kabupaten::where('id_kab', '=', $d->city_id)->first();
          return $d;
        });
        
        return view('default.category', ['data' => $data, 'category' => $category]);
    }// End Method
}
