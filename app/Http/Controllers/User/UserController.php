<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required|',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:10',
            'cpassword' => 'required|min:5|max:10|same:password'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $save = $user->save();

        if($save){
            return redirect()->back()->with('success', 'You are now registered successfully');
        }else{
            return redirect()->back()->with('fail', 'Something went wrong, failed to register');
        }
    }

    public function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:5|max:10'
        ],[
            'email.exists' => 'This email is not exists'
        ]);

        $creds = $request->only('email','password');
        if(Auth::attempt($creds)){
            return redirect()->route('user.home');
        }else{
            return redirect()->route('user.login')->with('fail', 'Incorrect credentials');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
