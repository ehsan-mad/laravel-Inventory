<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    //
    public function productPage()
    {
        return view('pages.product.productPage');
    }
}
