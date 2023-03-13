<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\User;



class AuthController extends Controller
{
    // Login functionality, and store the user login info.
    public function login() {
        //Checking user logged in
        if (auth()->check()) {
            return $response = [
                'has_logged_in' => true,
                'api_token' => Auth::user()->api_token
            ];
        }
        
        //Generating new token
        $token = Str::random(60);

        //Validating request form
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        
        $email = request('email');
        $password = request('password');

        $auth = Auth::attempt(['email' => $email, 'password' => $password]);
        
        if (!$auth)
            abort(401, 'unauthorized user, please login with proper email and password');

        //Inserting new token
        $user = User::where('id', Auth::user()->id)->update([
            'api_token' => $token
        ]);

        $auth = User::where('id', Auth::user()->id)->first();
        $response = [
            'api_token' => $auth->api_token
        ];
        return $response;
    }

    public function logout(){
        Auth::logout();
        return ['msg' => "logout success"];
    }
}    
