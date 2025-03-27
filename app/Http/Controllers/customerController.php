<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class customerController extends Controller
{
    //
    public function customerPage()
    {
        return view('pages.customer.customerPage');
    }
}
