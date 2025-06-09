<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class reportController extends Controller
{
    //
    public function salesReport(Request $request)
    {

        $user_id = $request->header('user_id');

        $from_date = date('Y-m-d', strtotime($request->from_date));
        $to_date   = date('Y-m-d', strtotime($request->to_date));

        $invoices = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();

        $discount = $invoices->sum('discount');
        $payable  = $invoices->sum('payable');
        $total    = $invoices->sum('total');
        $vat      = $invoices->sum('vat');
        $list     = $invoices;

        $data = [
            'discount'  => $discount,
            'payable'   => $payable,
            'total'     => $total,
            'vat'       => $vat,
            'list'      => $list,
            'from_date' => date('Y-m-d', strtotime($request->from_date)),
            'to_date'   => date('Y-m-d', strtotime($request->to_date)),
        ];

        // ✅ Remove dd() for production, or comment it out
        // dd($data);

        $pdf = Pdf::loadView('report.sale_report', $data);
        return $pdf->download('sale_report.pdf');
        // return view('pages.dashboard.report-page');
    }
    public function reportPage()
    {
        return view('pages.dashboard.report-page');
    }

}
