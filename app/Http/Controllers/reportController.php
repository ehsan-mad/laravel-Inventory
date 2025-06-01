<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf ;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    //
    function salesReport(Request $request){

        $user_id = $request->header('user_id');
        $from_date= date('Y-m-d', strtotime($request->input('from_date')));
        $to_date  = date('Y-m-d', strtotime($request->input('to_date')));
        $discount = Invoice::
            where('user_id', $user_id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('discount');
        $payable= Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('payable');
        $total = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('total');
        $vat = Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('vat');

        $list= Invoice::where('user_id', $user_id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->get();
       
            $data= [
                'report' => $discount,
                'rm'     =>  $payable,
                'total'  => $total,
                'vat'    => $vat,
                'list'   => $list,
            ];

            $pdf= Pdf::loadView('report.sales-report', $data);
            return $pdf->download('sales-report.pdf');
        // return view('pages.dashboard.report-page');
    }

    
}
