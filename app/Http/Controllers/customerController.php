<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function customerPage()
    {
        return view('pages.customer.customerPage');
    }
}
