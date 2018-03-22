<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\Provinsi;

class KabupatenController extends Controller
{
    public function index(Request $request)
    {
			$kabupatens = Kabupaten::paginate(10);
    	$kabupatens->appends(['page']);
    	return view('admin.kabupaten',[
				'kabupatens' => $kabupatens
    	]);
    }

    public function add()
    {
    	$kabupaten = new Kabupaten();
			$provinces = Provinsi::all();
			$action = route('admin-kabupaten-store');
    	$title  = trans('misc.add_new');
    	return view('admin.form-kabupaten', compact('kabupaten', 'provinces', 'action', 'title'));
    }

    public function store(Request $request)
    {
    	$kabupaten = new Kabupaten();
    	$kabupaten->id_kab = $request->id_kab;
      $kabupaten->id_prov = $request->id_prov;
    	$kabupaten->nama = $request->nama;
      $kabupaten->id_jenis = $request->id_jenis;
    	if ($kabupaten->save()) {
    		return redirect(route('admin-kabupaten-index'))->with('success_message', trans('admin.success_add'));
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request, $id)
    {
    	if (!$kabupaten = Kabupaten::where('id_kab',$id)->first()) {
    		return redirect()->back()->with('error_message', 'Kabupaten/Kota not found');
    	}
    	$action = route('admin-kabupaten-update', $id);
			$title  = 'Edit Kabupaten';
			$provinces = Provinsi::all();
			return view('admin.form-kabupaten', compact('kabupaten', 'action', 'title', 'provinces'));
    }

    public function update(Request $request, $id)
    {
    	if (!$kabupaten = Kabupaten::where('id_kab',$id)->first()) {
    		return redirect()->back()->with('error_message', 'Kabupaten/Kota not found');
    	}
      $kabupaten->id_kab = $request->id_kab;
      $kabupaten->id_prov = $request->id_prov;
    	$kabupaten->nama = $request->nama;
      $kabupaten->id_jenis = $request->id_jenis;
    	if ($kabupaten->save()) {
    		return redirect(route('admin-kabupaten-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
