<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class userController extends Controller
{
    //
    public function userPage()
    {
        return view('pages.auth.register');
    }

    public function loginPage(){
        return view('pages.auth.loginpage');
    }
    public function resetPage(){
        return view('pages.auth.passwordreset');
    }

    public function sendOtpPage(){
        return view('pages.auth.sendotp');
    }
    
    public function verifyOtpPage(){
        return view('pages.auth.verifyotp');
    }
    public function profilePage(){
        return view('pages.dashboard.profile');
    }
}
