<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class productController extends Controller
{
    //
    public function productPage()
    {
        return view('pages.product.productPage');
    }
}
