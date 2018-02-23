<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunTransaksi;

class AkunTransaksiController extends Controller
{
    public function index(Request $request)
    {
    	$akunKodes = AkunTransaksi::paginate(10);
    	$akunKodes->appends(['page']);
    	return view('admin.akun-transaksi',[
    		'akunKodes' => $akunKodes
    	]);
    }

    public function add()
    {
    	$akun = new AkunTransaksi();
    	$action = route('admin-akun-transaksi-store');
    	$title  = trans('misc.add_new');
    	return view('admin.form-akun-transaksi', compact('akun', 'action', 'title'));
    }

    public function store(Request $request)
    {
    	$akun = new AkunTransaksi();
    	$akun->nama = $request->nama;
    	$akun->nomor = $request->nomor;
    	if ($akun->save()) {
    		return redirect(route('admin-akun-transaksi-index'))->with('success_message', trans('admin.success_add'));
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request, $id)
    {
    	if (!$akun = AkunTransaksi::find($id)) {
    		return redirect()->back()->with('error_message', 'akun not found');
    	} 
    	$action = route('admin-akun-transaksi-update', $id);
    	$title  = 'Edit akun';
    	return view('admin.form-akun-transaksi', compact('akun', 'action', 'title'));
    }

    public function update(Request $request, $id)
    {
    	if (!$akun = AkunTransaksi::find($id)) {
    		return redirect()->back()->with('error_message', 'akun not found');
    	}
    	$akun->nama = $request->nama;
    	$akun->nomor = $request->nomor;
    	if ($akun->save()) {
    		return redirect(route('admin-akun-transaksi-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
