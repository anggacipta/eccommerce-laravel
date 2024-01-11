<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rules;

class VendorController extends Controller
{
    public function vendorDashboard(){
        return view('vendor.index');
    }

    public function vendorLogin()
    {
        return view('vendor.vendor_login');
    }

    public function vendorLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Vendor logout success',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.login')->with($notification);
    }

    public function vendorProfile(){
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile', compact('vendorData'));
    }

    public function vendorProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;
        $photo = $data->photo;
        if($request->file('photo')) {
            $file = $request->file('photo');
            if (!$photo == NULL) {
                unlink(public_path('upload/vendor_images/'.$photo));
            }
            $filename = date('YmdHi').$file->getClientOriginalName();
            Image::make($file)->resize(100, 100)->save('upload/vendor_images/'.$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Vendor Profile updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function vendorChangePassword(){
        return view('vendor.vendor_change_password');
    }

    public function vendorUpdatePassword(Request $request){
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

    public function becomeVendor()
    {
        return view('auth.become_vendor');
    }

    public function vendorRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vendor_join' => $request->vendor_join,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor Registered successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.login')->with($notification);
    }
}
