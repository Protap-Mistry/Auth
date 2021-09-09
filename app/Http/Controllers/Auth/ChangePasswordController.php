<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class ChangePasswordController extends Controller
{
    //after changing password, redirect login page but when back-press then can't go to before(change_password) page
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.passwords.change_pass');
    }

    public function change(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hashPassword= Auth::user()->password;

        if(Hash::check($request->oldPassword, $hashPassword))
        {
            $user= User::find(Auth::id());
            $user->password= Hash::make($request->password);
            $user->save();

            Auth::logout();

            return redirect()->route('login')->with('successMsg', "Password changed successfully !!!");
        }
        else{
            return redirect()->back()->with('errorMsg', "Current password is invalid !!!");
        }
    }
}
