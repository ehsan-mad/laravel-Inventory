<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    function InvoicePage(){
        return view('pages.Invoice.invoice-listPage');
    }
}
