<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
    	$kategoris = Kategori::paginate(10);
    	$kategoris->appends(['page']);
    	return view('admin.kategori',[
    		'kategoris' => $kategoris
    	]);
    }

    public function add()
    {
    	$kategori = new Kategori();
    	$action = route('admin-kategori-store');
    	$title  = trans('misc.add_new');
    	return view('admin.form-kategori', compact('kategori', 'action', 'title'));
    }

    public function store(Request $request)
    {
    	$kategori = new Kategori();
    	$kategori->nama = $request->nama;
    	$kategori->is_active = $request->input('is_active', 0);
    	if ($kategori->save()) {
    		return redirect(route('admin-kategori-index'))->with('success_message', trans('admin.success_add'));
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request, $id)
    {
    	if (!$kategori = Kategori::find($id)) {
    		return redirect()->back()->with('error_message', 'Kategori not found');
    	} 
    	$action = route('admin-kategori-update', $id);
    	$title  = 'Edit Kategori';
    	return view('admin.form-kategori', compact('kategori', 'action', 'title'));
    }

    public function update(Request $request, $id)
    {
    	if (!$kategori = Kategori::find($id)) {
    		return redirect()->back()->with('error_message', 'Kategori not found');
    	}
    	$kategori->nama = $request->nama;
    	$kategori->is_active = $request->is_active;
    	if ($kategori->save()) {
    		return redirect(route('admin-kategori-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
