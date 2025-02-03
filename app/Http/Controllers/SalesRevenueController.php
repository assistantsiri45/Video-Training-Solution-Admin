<?php

namespace App\Http\Controllers;

use App\Exports\CallRequestExport;
use App\Exports\SalesRevenueReportExport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\SubjectPackage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class SalesRevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Order::with('student', 'payment', 'associate.user', 'orderItems.package');

            return DataTables::of($query)
                ->filter(function ($query) {
                    if(!(request()->filled('filter.search')) && !(request()->filled('filter.date')) && !(request()->filled('filter.status'))){
                       
                        $year = date('Y');
                        $month = date('m');
                        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
                        $query->whereDate('created_at', '>=', $startOfMonth);
                    }
                    if(!(request()->filled('filter.date')) && (request()->filled('filter.status'))){
                       
                        $year = date('Y');
                        $month = date('m');
                        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
                        $query->whereDate('created_at', '>=', $startOfMonth);
                    }
            
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            /*$query->where(function ($query) {

                            })->orWhere(function ($query) {

                            });*/

                            $query
                                ->where('id', request()->input('filter.search'))
                                ->orWhere('transaction_id', request()->input('filter.search'))
                                ->orWhereHas('student', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                                        ->orWhere('email', 'like', '%' . request()->input('filter.search') . '%')
                                        ->orWhere('phone', 'like', '%' . request()->input('filter.search') . '%');
                                });

                            if (is_double(request()->input('filter.search'))) {
                                $query->orWhere('net_amount', request()->input('filter.search'));
                            }
                        });
                    }

                    if (request()->filled('filter.date')) {
                        $dateRange = request()->input('filter.date');
                        $explodedDates = explode(' - ', $dateRange);
                        $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                        $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);

                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    }

                    if (request()->filled('filter.status')) {
                        if (request()->input('filter.status') == '1') {
                            $query->where('payment_status', Order::PAYMENT_STATUS_SUCCESS);
                        }

                        if (request()->input('filter.status') == '0') {
                            $query->where('payment_status', Order::PAYMENT_STATUS_FAILED);
                        }
                    }
                })
                ->addColumn('created_at', function($query) {
                    return optional($query->created_at)->format('d/m/y H:i');
                })
                ->addColumn('packages', function($query) {
                    $packages = [];
                    $orderItems = $query->orderItems;

                    foreach ($orderItems as $orderItem) {
                        if (! $orderItem->package) {
                            continue;
                        }

                        if ($orderItem->item_type != OrderItem::ITEM_TYPE_PACKAGE) {
                            $packages[] = $orderItem->package->name . '(Study Material)';
                            continue;
                        }

                        if (! $orderItem->is_prebook) {
                            $packages[] = $orderItem->package->name;
                            continue;
                        }

                        if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                            $packages[] = $orderItem->package->name . ' ' . '<span class="badge badge-primary">Pre-Book</span>';
                            continue;
                        }

                        $packages[] = $orderItem->package->name . ' ' . '<span class="badge badge-primary">Fully-Paid</span>';

                        /*if ($orderItem->item_type == OrderItem::ITEM_TYPE_PACKAGE) {
                            if ($orderItem->is_prebook) {
                                if ($orderItem->package) {
                                    if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                                        $packages[] = $orderItem->package->name . ' ' . '<span class="badge badge-primary">Pre-Book</span>';
                                    }

                                    if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                                        $packages[] = $orderItem->package->name . ' ' . '<span class="badge badge-primary">Fully-Paid</span>';
                                    }
                                }
                            } else {
                                if ($orderItem->package) {
                                    $packages[] = $orderItem->package->name;
                                }
                            }
                        } else {
                            if ($orderItem->package) {
                                $packages[] = $orderItem->package->name . '(Study Material)';
                            }
                        }*/
                    }

                    return implode(', ', $packages);
                })
                ->addColumn('professors', function($query) {
                    $orderPackageIDs = $query->orderItems->pluck('package_id');
                    $packageIDs = [];

                    foreach ($orderPackageIDs as $orderPackageID) {
                        $package = Package::find($orderPackageID);

                        if ($package) {
                            if ($package->type == Package::TYPE_SUBJECT_LEVEL) {
                                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');

                                foreach ($chapterPackageIDs as $chapterPackageID) {
                                    $packageIDs[] = $chapterPackageID;
                                }
                            } else {
                                $packageIDs[] = $package->id;
                            }
                        }
                    }

                    $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id');
                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                    return implode(', ', $professorNames);
                })
                ->editColumn('net_amount', function ($query) {
                    if ($query->orderItems) {
                        return $query->net_amount;
                        // $sum = [];

                        // foreach ($query->orderItems as $orderItem) {
                        //     if (! $orderItem->is_prebook) {
                        //         $sum[] = $orderItem->price;
                        //     }

                        //     if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                        //         $sum[] = $orderItem->booking_amount;
                        //     }

                        //     if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                        //         $sum[] = ($orderItem->booking_amount + $orderItem->balance_amount);
                        //     }
                        // }

                        // return '₹' . array_sum($sum);
                    }

                    return '-';
                })
                ->editColumn('transaction_id', function($query) {
                    if ($query->transaction_id) {
                        return $query->transaction_id;
                    }

                    return '-';
                })
                ->editColumn('is_refunded', function($query) {
                    if ($query->is_refunded==1) {
                        return '✓';
                    }

                })
                ->rawColumns(['packages'])
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'width' => '10%'],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Name', 'width' => '10%', 'defaultContent' => ''],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email', 'width' => '10%', 'defaultContent' => ''],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone', 'width' => '10%', 'defaultContent' => ''],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID', 'width' => '10%'],
            ['data' => 'transaction_response_status', 'name' => 'transaction_response_status', 'title' => 'Response Status', 'width' => '10%'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount', 'width' => '10%'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At', 'width' => '10%', 'orderable' => false],
            ['data' => 'packages', 'name' => 'packages', 'title' => 'Packages', 'width' => '10%', 'orderable' => false],
            ['data' => 'professors', 'name' => 'professors', 'title' => 'Professors', 'width' => '10%', 'orderable' => false],
            ['data' => 'is_refunded', 'name' => 'is_refunded', 'title' => 'Refunded'],
            ['data' => 'payment.receipt_no', 'name' => 'payment.receipt_no', 'title' => 'Invoice#','defaultContent'=>''],
            ['data' => 'associate.user.name', 'name' => 'associate.user.name', 'title' => 'Associate Name', 'defaultContent' => ''],
            ['data' => 'commission', 'name' => 'commission', 'title' => 'Commission'],
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'lengthChange' => false,
            'bInfo' => false,
            'pageLength'=> 8,
        ])->orderBy(0, 'desc');

        return view('pages.salesrevenue.index', compact('table'));
    }

    public function export()
    {
        set_time_limit(300);
        $search = request()->input('export_search') ?? '';
        $fromDate = null;
        $toDate = null;
        $status = null;

        if (request()->filled('export_created_at')) {
            $dateRange = request()->input('export_created_at');
            $explodedDates = explode(' - ', $dateRange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
            $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
        }

        if (request()->input('export_status') == '1') {
            $status = Order::PAYMENT_STATUS_SUCCESS;
        }

        if (request()->input('export_status') == '0') {
            $status = Order::PAYMENT_STATUS_FAILED;
        }

        return Excel::download(new SalesRevenueReportExport($search, $fromDate, $toDate, $status), 'SALES_REPORT' . time() . '.csv');
    }

}
