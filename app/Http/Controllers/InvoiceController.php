<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //
    function InvoiceCreate(){
        DB::beginTransaction();
        try {
            // You can perform any database operations here if needed
           
            $user_id = request()->header('user_id');

           $invoice= Invoice::create([
                'user_id' => $user_id,
                'discount' => request()->input('discount'),
                'customer_id' => request()->input('customer_id'),
                'vat' => request()->input('vat'),
                'payable' => request()->input('payable'),
                'total' => request()->input('total'),
                
            ]);

            $products = request()->input('products');
            foreach ($products as $product) {
                InvoiceProduct::create([
                    'user_id' => $user_id,
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['product_id'],
                    'user_id' => $user_id,
                    
                    'qty' => $product['qty'],
                    'sale_price' => $product['sale_price'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction failed: ' . $e->getMessage(),
            ], 500);
        }
        // return view('pages.Invoice.invoice-listPage');
    }
}
