<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class reportController extends Controller
{
    //
    function reportPage(){
        return view('pages.dashboard.report-page');
    }
}
