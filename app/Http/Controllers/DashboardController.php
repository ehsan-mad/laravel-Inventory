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
    public function dashboardPage()
    {
        return view('pages.dashboard.dashboard');
    }
    public function summary(Request $request)
    {
        $product  = Product::where('user_id', request()->header('user_id'))->count();
        $category = Category::where('user_id', request()->header('user_id'))->count();
        $customer = Customer::where('user_id', request()->header('user_id'))->count();
        $invoice  = Invoice::where('user_id', request()->header('user_id'))->count();
        // $vat      = Invoice::where('user_id', request()->header('user_id'))->sum('vat');
        // $pay      = Invoice::where('user_id', request()->header('user_id'))->sum('payable');
        // $total    = Invoice::where('user_id', request()->header('user_id'))->sum('total');

        $sum = Invoice::where('user_id', request()->header('user_id'))->SelectRaw('SUM(total) as total, SUM(vat) as vat, SUM(payable) as payable')->first();
        return response()->json([
            'status'  => 'success',
            'message' => 'Dashboard summary fetched successfully',
            'data'    => [
                'product'  => $product,
                'category' => $category,
                'customer' => $customer,
                'invoice'  => $invoice,
                'vat'      => $sum->vat ?? 0,
                'payable'  => $sum->payable ?? 0,
                'total'    => $sum->total ?? 0,
            ],
        ], 200);
        // return view('pages.dashboard.dashboard');
    }

}
