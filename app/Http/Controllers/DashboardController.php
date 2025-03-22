<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    function dashboardPage(){
        return view('pages.dashboard.dashboard');
    }
}
