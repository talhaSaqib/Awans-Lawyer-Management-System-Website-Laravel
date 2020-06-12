<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email|unique:users',
                'fullname' => 'required|max:120',
                'password' => 'required|min:5'
            ]);

        $email = $request['email'];
        $fullname = $request['fullname'];
        $password = bcrypt($request['password']);

        $user = new User();
        $user->email = $email;
        $user->fullname = $fullname;
        $user->password = $password;
        $user->role = "client";

        $user->save();
        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required',
                'password' => 'required|min:5'
            ]);

        if(Auth::attempt( ['email' => $request['email'], 'password' => $request['password']] ))
        {
            return redirect()->route('home');
        }
        return redirect()->route('login')->with('message','Sorry, You must not be registered.');
    }


}
