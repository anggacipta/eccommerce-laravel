<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function allSlider()
    {
        $sliders = Slider::latest()->get();
        return view('backend.admin.slider.slider_all', compact('sliders'));
    }

    public function addSlider()
    {
        return view('backend.admin.slider.slider_add');
    }

    public function storeSlider(Request $request)
    {
        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(2370, 807)->save('upload/slider/' . $name_gen);
        $save_url = 'upload/slider/' . $name_gen;

        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' =>  $save_url
        ]);

        $notification = array(
            'message' => 'Slider inserted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);
    }

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('backend.admin.slider.slider_edit', compact('slider'));
    }

    public function updateSlider(Request $request)
    {
        $id = $request->id;
        $old_image = $request->old_image;

        if ($request->file('slider_image')) {
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(2370, 807)->save('upload/slider/' . $name_gen);
            $save_url = 'upload/slider/' . $name_gen;

            if (file_exists($old_image)) {
                unlink($old_image);
            }

            Slider::findOrFail($id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' =>  $save_url
            ]);

            $notification = array(
                'message' => 'Slider updated with image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        } else {
            Slider::findOrFail($id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
            ]);

            $notification = array(
                'message' => 'Slider updated without image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        }
    }

    public function deleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $old_image = $slider->slider_image;
        unlink($old_image);
        $slider->delete();

        $notification = array(
            'message' => 'Slider delete successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}
