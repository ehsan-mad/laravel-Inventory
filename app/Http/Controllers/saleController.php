<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class saleController extends Controller
{
    //
    public function salePage()
    {
        return view('pages.dashboard.sale-page');
    }
}
