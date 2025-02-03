<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PaymentExport;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return View
     */
    public function index(Builder $builder)
    {
        $query = Payment::query()->with('order')->where('payment_status', Payment::PAYMENT_STATUS_SUCCESS)->latest();

        if (request()->ajax()) {
            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.search')) {
                        $search = '%' . request()->input('filter.search') . '%';

                        $query->where(function ($query) use ($search) {
                            $query->whereHas('student', function ($query) use ($search) {
                                $query->where('name', 'like', $search);
                            })
                                ->orWhere('transaction_id', 'like', $search)
                                ->orWhere('receipt_no', 'like', $search);
                        });
                    }
                })
                ->addColumn('name', function($query) {
                    return $query->student->name ?? '-';
                })
                ->addColumn('receipt_no', function($query) {
                    return $query->receipt_no ?? '-';
                })
                ->addColumn('transaction_id', function($query) {
                    return $query->transaction_id ?? '-';
                })
                ->addColumn('net_amount', function($query) {
                    return  $query->net_amount ?? '-';
                })
                ->addColumn('gst', function($query) {
                    if ($query->cgst_amount && $query->sgst_amount) {
                        return  round($query->cgst_amount) . ' ( ' . $query->cgst . '% ) ' . ' + ' . round($query->sgst_amount) . ' ( ' . $query->sgst . '% ) ';
                    }

                    if ($query->igst) {
                        return  round($query->igst_amount) . ' ( ' . $query->igst . '% ) ';
                    }
                    else
                    {
                        return '-';
                    }
                })
                ->addColumn('payment_gateway', function($query) {
                    if ($query->payment_updated_method == Payment::UPDATE_METHOD_EASEBUZZ)
                    {
                       return  $status= '<span class="badge badge-primary">EASEBUZZ</span>';
                    }else{
                       return $status= '<span class="badge badge-info">CCAVENUE</span>';
                    }
                    
                })
                ->addColumn('payment_status', function($query) {
                    if ($query->payment_status == Payment::PAYMENT_STATUS_SUCCESS)
                    {
                        $status= '<span class="badge badge-success">Success</span>';
                    }
                    if ($query->payment_status == Payment::PAYMENT_STATUS_FAILURE)
                    {
                        $status= '<span class="badge badge-danger">Failed</span>';
                    }
                    if($query->order->is_refunded==1)
                    {
                        $refund = '<span class="badge badge-danger">Refunded</span>';
                    }
                    else
                    {
                        $refund = '';
                    }

                    return $status.' '.$refund;
                })
                ->addColumn('created_at', function($query) {
                    return $query->created_at->toDayDateTimeString();
                })
                ->rawColumns(['net_amount','payment_gateway', 'payment_status'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'receipt_no', 'name' => 'receipt_no', 'title' => 'Receipt No.'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'gst', 'name' => 'gst', 'title' => 'GST'],
            ['data' => 'payment_gateway', 'name' => 'payment_gateway', 'title' => 'Payment Gateway'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
        ]);

        $successPaymentCount = Payment::query()->where('payment_status', Payment::PAYMENT_STATUS_SUCCESS)->count();
        $failedPaymentCount = Payment::query()->where('payment_status', Payment::PAYMENT_STATUS_FAILURE)->count();
        $totalSuccessfulAmount = Payment::query()->where('payment_status', Payment::PAYMENT_STATUS_SUCCESS)->sum('net_amount');

        return view('pages.reports.payments.index', compact('html', 'successPaymentCount', 'failedPaymentCount', 'totalSuccessfulAmount'));
    }

    public function export()
    {
        $search = request()->input('export_search') ?? '';

        return Excel::download(new PaymentExport($search), 'PAYMENTS_' . time() . '.csv');
    }
}
