<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageExtension;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class PackageExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = OrderItem::with('package', 'user', 'payment')->where('payment_status', '!=', 0)
            ->latest();

            if (request()->filled('filter.order_id')) {
                $query->where('order_id', request()->input('filter.order_id'));
            }
            if (request()->filled('filter.receipt_no')) {
                $query->whereHas('payment', function ($query){
                    $query->where( 'receipt_no', request()->input('filter.receipt_no'));
                });
            }
            if (request()->filled('filter.dop')) {
                $query->whereHas('payment', function ($query){
                    $query->where( 'created_at', 'like', '%' . request()->input('filter.dop') . '%');
                });
            }
            if (request()->filled('filter.student_name')) {
                $query->whereHas('user', function ($query){
                    $query->where( 'name', 'like', '%' . request()->input('filter.student_name') . '%');
                });
            }



            return DataTables::of($query)
                ->editColumn('package_name', function ($query) {
                    return $query->package->name ?? '';
                })
                ->editColumn('price', function ($query) {
                    return $query->package->price ?? '';
                })
                ->editColumn('student_name', function ($query) {
                    return $query->user->name ?? '';
                })
                ->editColumn('receipt_no', function ($query) {
                    return $query->payment->receipt_no ?? '';
                })
                ->editColumn('date_of_payment', function ($query) {
                    return Carbon::parse($query->created_at)->format("Y-m-d");
                })
                ->editColumn('expiry_date', function ($query) {
                    $extended_date = PackageExtension::where('order_item_id', $query->id)->latest()->first();
                    if ($extended_date) {
                        return Carbon::parse($extended_date->extended_date)->format("Y-m-d");
                    }
                    return Carbon::parse($query->created_at->addMonths(env('VALIDITY_IN_MONTHS')))->format("Y-m-d");
                })

                ->addColumn('action', 'pages.packages.package_extensions.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order ID'],
            ['data' => 'receipt_no', 'name' => 'receipt_no', 'title' => 'Receipt No'],
            ['data' => 'package_name', 'name' => 'package_name', 'title' => 'Package Name'],
            ['data' => 'student_name', 'name' => 'student_name', 'title' => 'Student Name'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'date_of_payment', 'name' => 'date_of_payment', 'title' => 'Date of Payment'],
            ['data' => 'expiry_date', 'name' => 'expiry_date', 'title' => 'Date of Expiry'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']

        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.package_extensions.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addExtension(Builder $builder, $id)
    {
        if (request()->ajax()) {
            $query = PackageExtension::where('order_item_id', $id)
                ->latest()->get();
            return DataTables::of($query)
                ->editColumn('created_at', function ($query){
                    return $query->created_at->format("Y-m-d");
                })
                ->editColumn('extended_date', function ($query){
                    return Carbon::parse($query->extended_date)->format("Y-m-d");
                })
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'extended_hours', 'name' => 'extended_hours', 'title' => 'Extended Hours'],
            ['data' => 'extended_date', 'name' => 'extended_date', 'title' => 'Extended Date'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created at'],

        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        $order_item_id = $id;
        $order_item = OrderItem::findOrFail($id);

        return view('pages.packages.package_extensions.create', compact('html', 'order_item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order_item_id = $request->input('order_item_id');
        $extended_hours = $request->input('extended_hours');
        $extended_date = $request->input('extended_date');

        if (empty($extended_date) and empty($extended_hours))
        {
            return redirect()->route('package-extensions.add-extension',  $request->input('order_item_id'))->with('error', 'Please enter extension date or hours');
        }

        $package_extension = new PackageExtension();
        $order_item = OrderItem::findOrFail($order_item_id);

        $current_date = Carbon::now();
        $package_expiry_date =  $order_item->created_at->addMonths(env('VALIDITY_IN_MONTHS'));

        if ($package_expiry_date > $current_date) {
            $current_date = $package_expiry_date;
        }

        $current_extension = PackageExtension::where('order_item_id', $order_item_id)->latest()->first();

        if ($current_extension) {
            $current_date = $current_extension->extended_date ;
        }

        if (!empty($extended_date) ) {
                if ($extended_date <= $current_date) {
                    return redirect()->route('package-extensions.add-extension',  $request->input('order_item_id'))->with('error', 'Date should be greater than current date or expiry date.');
                }
                $package_extension->extended_date = $extended_date;
        }
        else {
            $package_extension->extended_date = $order_item->created_at->addMonths(env('VALIDITY_IN_MONTHS'));
            if ($current_extension) {
                $package_extension->extended_date = $current_extension->extended_date;
            }
        }

        $package_extension->order_item_id = $request->input('order_item_id');
        $package_extension->extended_hours = $request->input('extended_hours');
        $package_extension->save();

        return redirect()->route('package-extensions.add-extension',  $request->input('order_item_id'))->with('success', 'Extension successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $extension = PackageExtension::where('order_item_id', $id)->first();
        if (!empty($extension)) {
            $extension->delete();

            return response()->json(true, 200);
        }
        return response()->json(false, 200);

    }
}
