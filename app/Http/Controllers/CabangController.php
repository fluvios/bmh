<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;

class CabangController extends Controller
{
    public function index(Request $request)
    {
    	$cabangs = Cabang::paginate(10);
    	$cabangs->appends(['page']);
    	return view('admin.cabang',[
    		'cabangs' => $cabangs
    	]);
    }

    public function add()
    {
    	$cabang = new Cabang();
    	$action = route('admin-cabang-store');
    	$title  = trans('misc.add_new');
    	return view('admin.form-cabang', compact('cabang', 'action', 'title'));
    }

    public function store(Request $request)
    {
    	$cabang = new Cabang();
    	$cabang->kode = $request->kode;
    	$cabang->nama = $request->nama;
    	if ($cabang->save()) {
    		return redirect(route('admin-cabang-index'))->with('success_message', trans('admin.success_add'));
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request, $id)
    {
    	if (!$cabang = Cabang::find($id)) {
    		return redirect()->back()->with('error_message', 'Cabang not found');
    	} 
    	$action = route('admin-cabang-update', $id);
    	$title  = trans('misc.edit_cabang');
    	return view('admin.form-cabang', compact('cabang', 'action', 'title'));
    }

    public function update(Request $request, $id)
    {
    	if (!$cabang = Cabang::find($id)) {
    		return redirect()->back()->with('error_message', 'Cabang not found');
    	}
    	$cabang->kode = $request->kode;
    	$cabang->nama = $request->nama;
    	if ($cabang->save()) {
    		return redirect(route('admin-cabang-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
