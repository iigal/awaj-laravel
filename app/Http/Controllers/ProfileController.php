<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $userdata = Auth::user();
        $allIssues = $userdata->issues()->get();

        return view('citizen.profile', compact('userdata', 'allIssues'));
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required',
            'address' => 'required',
            'phonenumber' => 'required',
        ]);

        $user->fullname = $request->fullname;
        $user->address = $request->address;
        $user->phonenumber = $request->phonenumber;
        $user->save();

        return redirect()->route('profile')->with('message', 'Profile updated successfully');
    }
}
