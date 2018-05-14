<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magazines;

use App\Http\Requests;
use Auth;

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
        $path_cover = 'public/magazine/cover/';

        if ($request->hasFile('magazine')) {
            $extension    = $request->file('magazine')->getClientOriginalExtension();
            $magazine_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
            $name = $request->file('magazine')->getClientOriginalName();

            if ($request->file('magazine')->move($temp, $magazine_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$magazine_large)) {
                    \File::copy($temp.$magazine_large, $path_large.$magazine_large);
                    \File::delete($temp.$magazine_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_large  = $magazine_large;
        }//<====== End HasFile

        if ($request->hasFile('image')) {
            $extension    = $request->file('image')->getClientOriginalExtension();
            $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);

            if ($request->file('image')->move($temp, $file_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_cover.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_cover_large  = $file_large;
        }//<====== End HasFile

        $magazine->cover = $image_cover_large;
        $magazine->name = $image_large;
        $magazine->filename = $name;

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
        $path_cover = 'public/magazine/cover/';

        if ($request->hasFile('magazine')) {
            $extension    = $request->file('magazine')->getClientOriginalExtension();
            $magazine_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
            $name = $request->file('magazine')->getClientOriginalName();

            if ($request->file('magazine')->move($temp, $magazine_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$magazine_large)) {
                    \File::copy($temp.$magazine_large, $path_large.$magazine_large);
                    \File::delete($temp.$magazine_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_large  = $magazine_large;
        }//<====== End HasFile

        if ($request->hasFile('image')) {
            $extension    = $request->file('image')->getClientOriginalExtension();
            $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);

            if ($request->file('image')->move($temp, $file_large)) {
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_cover.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_cover_large  = $file_large;
        }//<====== End HasFile

        $magazine->cover = $image_cover_large;
        $magazine->name = $image_large;
        $magazine->filename = $name;
        
    	if ($magazine->save()) {
    		return redirect(route('admin-magazine-index'))->with('success_message', trans('admin.success_update'));
    	} else {
    		return redirect()->back();
    	}
    }
}
