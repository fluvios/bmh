<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Slider;
use Illuminate\Http\Request;

use Auth;
use Image;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::paginate(10);
        $sliders->appends(['page']);
        return view('admin.slider', [
            'sliders' => $sliders
        ]);
    }

    public function add()
    {
        $slider = new Slider();
        $action = route('admin-slider-store');
        $title  = trans('misc.add_new');
        return view('admin.form-slider', compact('slider', 'action', 'title'));
    }

    public function store(Request $request)
    {
        $slider = new Slider();
        $temp = 'public/temp/';
        $path_small = 'public/campaigns/small/';
        $path_large = 'public/campaigns/large/';

        if ($request->hasFile('image')) {
            $extension    = $request->file('image')->getClientOriginalExtension();
            $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
            $file_small     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
      
            if ($request->file('image')->move($temp, $file_large)) {
                set_time_limit(0);
      
                //=============== Image Large =================//
                $width  = Helper::getWidth($temp.$file_large);
                $height = Helper::getHeight($temp.$file_large);
                // $max_width = '800';
      
                // if ($width < $height) {
                //     $max_width = '400';
                // }
      
                // if ($width > $max_width) {
                //     $scale = $max_width / $width;
                //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
                // } else {
                //     $scale = 1;
                //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
                // }
      
                //=============== Small Large =================//
                // Helper::resizeImageFixed($temp.$file_large, 400, 300, $temp.$file_small);
      
                //======= Copy Folder Small and Delete...
                if (\File::exists($temp.$file_small)) {
                    \File::copy($temp.$file_small, $path_small.$file_small);
                    \File::delete($temp.$file_small);
                }//<--- IF FILE EXISTS
      
                Image::make($temp.$file_large)->orientate();
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_large.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_small  = $file_small;
            $image_large  = $file_large;
        }//<====== End HasFile

        $slider->image = $image_large;
        $slider->isActive = $request->input('isActive', 0);
        $slider->category_id = $request->input('category_id');
        if ($slider->save()) {
            return redirect(route('admin-slider-index'))->with('success_message', trans('admin.success_add'));
        } else {
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        if (!$slider = Slider::find($id)) {
            return redirect()->back()->with('error_message', 'Image not found');
        }
        $action = route('admin-slider-update', $id);
        $title  = 'Edit Slider';
        return view('admin.form-slider', compact('slider', 'action', 'title'));
    }

    public function update(Request $request, $id)
    {
        $temp = 'public/temp/';
        $path_small = 'public/campaigns/small/';
        $path_large = 'public/campaigns/large/';

        if (!$slider = Slider::find($id)) {
            return redirect()->back()->with('error_message', 'Slider not found');
        }

        if ($request->hasFile('image')) {
            $extension    = $request->file('image')->getClientOriginalExtension();
            $file_large     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
            $file_small     = strtolower(Auth::user()->id.time().str_random(40).'.'.$extension);
      
            if ($request->file('image')->move($temp, $file_large)) {
                set_time_limit(0);
      
                //=============== Image Large =================//
                $width  = Helper::getWidth($temp.$file_large);
                $height = Helper::getHeight($temp.$file_large);
                // $max_width = '800';
      
                // if ($width < $height) {
                //     $max_width = '400';
                // }
      
                // if ($width > $max_width) {
                //     $scale = $max_width / $width;
                //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
                // } else {
                //     $scale = 1;
                //     $uploaded = Helper::resizeImage($temp.$file_large, $width, $height, $scale, $temp.$file_large);
                // }
      
                //=============== Small Large =================//
                // Helper::resizeImageFixed($temp.$file_large, 400, 300, $temp.$file_small);
      
                //======= Copy Folder Small and Delete...
                if (\File::exists($temp.$file_small)) {
                    \File::copy($temp.$file_small, $path_small.$file_small);
                    \File::delete($temp.$file_small);
                }//<--- IF FILE EXISTS
      
                Image::make($temp.$file_large)->orientate();
      
                //======= Copy Folder Large and Delete...
                if (\File::exists($temp.$file_large)) {
                    \File::copy($temp.$file_large, $path_large.$file_large);
                    \File::delete($temp.$file_large);
                }//<--- IF FILE EXISTS
            }
      
            $image_small  = $file_small;
            $image_large  = $file_large;
            $slider->image = $image_large;
        }//<====== End HasFile

        $slider->isActive = $request->input('isActive', 0);
        $slider->category_id = $request->input('category_id');
        if ($slider->save()) {
            return redirect(route('admin-slider-index'))->with('success_message', trans('admin.success_update'));
        } else {
            return redirect()->back();
        }
    }
}
