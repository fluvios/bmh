<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\ReferralDonasi;
use App\Models\ReferralRegistrasi;
use App\Models\Donations;
use App\Models\User;

use DB;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $registrasi = ReferralRegistrasi::groupBy('email')
        ->select('email', DB::raw('sum(bonus) as bonus'))
        ->where('status', 'pending')->paginate(10);
		$registrasi->appends(['page']);
		$donasi = ReferralDonasi::groupBy('email')
        ->select('email', DB::raw('sum(bonus) as bonus'))
        ->where('status', 'pending')->paginate(10);
		$donasi->appends(['page']);
    	return view('admin.bonus',[
            'registrasi' => $registrasi,
            'donasi' => $donasi
    	]);
    }

    public function detailDonasi($id)
    {
    	$bonus = ReferralDonasi::where('email', $id)->where('status', 'pending')->get();
    	$action = route('admin-bonus-detail-donasi');
    	$title  = 'Affiliator Bonus Detail';
    	return view('admin.detil-bonus-donasi', compact('bonus', 'action', 'title'));
    }

    public function detailRegistrasi($id)
    {
    	$bonus = ReferralRegistrasi::where('email', $id)->where('status', 'pending')->get();
    	$action = route('admin-bonus-detail-registrasi');
    	$title  = 'Affiliator Bonus Detail';
    	return view('admin.detil-bonus-registrasi', compact('bonus', 'action', 'title'));
    }

    public function acceptDonasi($id)
    {
        # Get the donation
        $donasi = ReferralDonasi::where('email', $id)->where('status', 'pending')->get();
        # Set Status to send        
        foreach ($donasi as $d) {
            $d->status = 'withdrawal';
            $d->save();
        }
        # redirect to first page
        return redirect(route('admin-bonus-index'))->with('success_message', trans('admin.success_add'));
    }

    public function acceptRegistrasi($id)
    {
        # Get the registrasi
        $registrasi = ReferralRegistrasi::where('email', $id)->where('status', 'pending')->get();
        # Set Status to send        
        foreach ($registrasi as $r) {
            $r->status = 'withdrawal';
            $r->save();
        }
        # redirect to first page
        return redirect(route('admin-bonus-index'))->with('success_message', trans('admin.success_add'));
    }
}
