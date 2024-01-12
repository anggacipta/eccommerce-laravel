<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    public function adminDashboard(){
        return view('admin.index');
    }

    public function adminLogin(){
        return view('admin.admin_login');
    }

    public function adminLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin logout success',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.login')->with($notification);
    }

    public function adminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
//        return view('admin.admin_profile_view', compact('adminData'));
        return User::all();
    }

    public function adminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $photo = $data->photo;
        if($request->file('photo')) {
            $file = $request->file('photo');
            if (!$photo == NULL) {
                unlink(public_path('upload/admin_images/'.$photo));
            }
            $filename = date('YmdHi').$file->getClientOriginalName();
            Image::make($file)->resize(100, 100)->save('upload/admin_images/'.$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
          'message' => 'Admin Profile updated',
          'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function adminChangePassword(){
        return view('admin.admin_change_password');
    }

    public function adminUpdatePassword(Request $request){
        // validation
        $request->validate([
           'old_password' => 'required',
           'new_password' => 'required|confirmed'
        ]);

        // match old password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old password doesn't match");
        }

        // update new password
        User::whereId(auth()->user()->id)->update([
           'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password Changed Successfully");
    }

    public function inactiveVendor()
    {
        $inactive_vendor = User::where('status', 'inactive')->where('role', 'vendor')->latest()->get();
        return view('backend.vendor.inactive_vendor', compact('inactive_vendor'));
    }

    public function activeVendor()
    {
        $active_vendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        return view('backend.vendor.active_vendor', compact('active_vendor'));
    }

    public function inactiveVendorDetails($id)
    {
        $inactive_vendor_details = User::findOrFail($id);
        return view('backend.vendor.inactive_vendor_details', compact('inactive_vendor_details'));
    }

    public function activeVendorDetails($id)
    {
        $active_vendor_details = User::findOrFail($id);
        return view('backend.vendor.active_vendor_details', compact('active_vendor_details'));
    }

    public function activeVendorApprove(Request $request)
    {
        $vendor_id = $request->id;
        User::findOrFail($vendor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor active successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('active.vendor')->with($notification);
    }

    public function inactiveVendorApprove(Request $request)
    {
        $vendor_id = $request->id;
        User::findOrFail($vendor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor inactive successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);
    }

}
