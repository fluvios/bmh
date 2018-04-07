<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magazines;

use App\Http\Requests;

class MagazinesController extends Controller
{
    public function index(Request $request)
    {
    	$magazines = Magazines::paginate(10);
    	$magazines->appends(['page']);
    	return view('admin.magazine',[
    		'magazines' => $magazines
    	]);
    }

    public function add()
    {
    	$magazine = new Magazines();
    	$action = route('admin-magazine-store');
    	$title  = trans('misc.add_new');
    	return view('admin.form-magazine', compact('magazine', 'action', 'title'));
    }

    public function store(Request $request)
    {
    	$magazine = new Magazines();
        $temp = 'public/temp/';
        $path_large = 'public/magazine/';

        if ($request->hasFile('image')) {
            $file_large     = $request->file('image')->getClientOriginalName();
      
            if ($request->file('image')->move($temp, $file_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_large.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_large  = $file_large;
        }//<====== End HasFile

        $magazine->name = $image_large;
    	if ($magazine->save()) {
    		return redirect(route('admin-magazine-index'))->with('success_message', trans('admin.success_add'));
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request, $id)
    {
    	if (!$magazine = Magazines::find($id)) {
    		return redirect()->back()->with('error_message', 'Magazines not found');
    	} 
    	$action = route('admin-magazine-update', $id);
    	$title  = trans('misc.edit_magazine');
    	return view('admin.form-magazine', compact('magazine', 'action', 'title'));
    }

    public function update(Request $request, $id)
    {
    	if (!$magazine = Magazines::find($id)) {
    		return redirect()->back()->with('error_message', 'Magazines not found');
    	}
        $temp = 'public/temp/';
        $path_large = 'public/magazine/';

        if ($request->hasFile('image')) {
            $file_large = $request->file('image')->getClientOriginalName();
      
            if ($request->file('image')->move($temp, $file_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_large.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_large  = $file_large;
        }//<====== End HasFile

        $magazine->nama = $image_large;
    	if ($magazine->save()) {
    		return redirect(route('admin-magazine-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
