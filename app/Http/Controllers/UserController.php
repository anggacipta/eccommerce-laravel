<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function userDashboard()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('index', compact('userData'));
    }

    public function userProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $photo = $data->photo;
        if($request->file('photo')) {
            $file = $request->file('photo');
            if (!$photo == NULL) {
                unlink(public_path('upload/user_images/'.$photo));
            }
            $filename = date('YmdHi').$file->getClientOriginalName();
            Image::make($file)->resize(100, 100)->save('upload/user_images/'.$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function userLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('login')->with($notification);
    }

    public function updatePasswordUser(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        $notificationError = array(
            'message' => "User Password doesn't match",
            'alert-type' => 'error'
        );

        // match old password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with($notificationError);
        }

        // update new password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notificationSuccess = array(
            'message' => 'User Password updated',
            'alert-type' => 'success'
        );

        return back()->with($notificationSuccess);
    }
}
