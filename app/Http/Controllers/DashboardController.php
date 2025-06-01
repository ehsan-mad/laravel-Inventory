<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function summary(Request $request)
    {
        $product  = Product::where('user_id', request()->header('user_id'))->count();
        $category = Category::where('user_id', request()->header('user_id'))->count();
        $customer = Customer::where('user_id', request()->header('user_id'))->count();
        $invoice  = Invoice::where('user_id', request()->header('user_id'))->count();
        $vat      = Invoice::where('user_id', request()->header('user_id'))->sum('vat');
        $pay      = Invoice::where('user_id', request()->header('user_id'))->sum('payable');
        $total    = Invoice::where('user_id', request()->header('user_id'))->sum('total');

        return response()->json([
            'status'  => 'success',
            'message' => 'Dashboard summary fetched successfully',
            'data'    => [
                'product'  => $product,
                'category' => $category,
                'customer' => $customer,
                'invoice'  => $invoice,
                'vat'      => $vat,
                'payable'  => $pay,
                'total'    => $total,
            ],
        ], 200);
        // return view('pages.dashboard.dashboard');
    }

}
