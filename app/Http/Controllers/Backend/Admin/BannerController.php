<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function allBanner()
    {
        $banners = Banner::latest()->get();
        return view('backend.admin.banner.all_banner', compact('banners'));
    }

    public function addBanner()
    {
        return view('backend.admin.banner.add_banner');
    }

    public function storeBanner(Request $request)
    {
        $image = $request->file('banner_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(768, 450)->save('upload/banner/' . $name_gen);
        $save_url = 'upload/banner/' . $name_gen;

        Banner::insert([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url,
            'banner_image' =>  $save_url
        ]);

        $notification = array(
            'message' => 'Banner inserted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.banner')->with($notification);
    }

    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.admin.banner.edit_banner', compact('banner'));
    }

    public function updateBanner(Request $request)
    {
        $id = $request->id;
        $old_image = $request->old_image;

        if ($request->file('banner_image')) {
            $image = $request->file('banner_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(768, 450)->save('upload/banner/' . $name_gen);
            $save_url = 'upload/banner/' . $name_gen;

            if (file_exists($old_image)) {
                unlink($old_image);
            }

            Banner::findOrFail($id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' =>  $save_url
            ]);

            $notification = array(
                'message' => 'Banner updated with image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);
        } else {
            Banner::findOrFail($id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
            ]);

            $notification = array(
                'message' => 'Banner updated without image successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);
        }
    }

    public function deleteBanner($id)
    {
       $banner = Banner::findOrFail($id);
       $banner_image = $banner->banner_image;
       unlink($banner_image);
       $banner->delete();

        $notification = array(
            'message' => 'Banner deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}
