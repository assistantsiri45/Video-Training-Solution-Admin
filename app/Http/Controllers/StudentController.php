<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Models\ImportLog;
use App\Imports\UsersImport;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\JMoney;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Student::query();
            $query->orderBy('name', 'asc');

            return DataTables::of($query)
                ->filter(function($query) {
                    if (!empty(request('filter.sign_up_date'))) {
                        $query->whereDate('created_at', Carbon::parse(request('filter.sign_up_date')));
                    }
                })
                ->addColumn('course', function($query) {
                    return $query->course->name ?? null;
                })
                ->addColumn('level', function($query) {
                    return $query->level->name ?? null;
                })
                ->addColumn('associate', function($query) {
                    return $query->associate->user->name ?? null;
                })
                ->addColumn('action', 'pages.students.action')
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'course', 'name' => 'course', 'title' => 'Course'],
            ['data' => 'level', 'name' => 'level', 'title' => 'Level'],
            ['data' => 'associate', 'name' => 'associate', 'title' => 'Associate'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.students.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $tableOrders = app(Builder::class)->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'associate', 'name' => 'associate', 'title' => 'Associate'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'coupon_amount', 'name' => 'coupon_amount', 'title' => 'Coupon Amount'],
            ['data' => 'reward_amount', 'name' => 'reward_amount', 'title' => 'Reward Amount'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Order Status']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ])->ajax(route('tables.orders') . '?user_id=' . $student->user_id)->setTableId('table-orders');

        $tableCart = app(Builder::class)->columns([
            ['data' => 'package_name', 'name' => 'package_name', 'title' => 'Package'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'added_at', 'name' => 'added_at', 'title' => 'Added At'],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ])->ajax(route('tables.cart') . '?user_id=' . $student->user_id)->setTableId('table-cart');

        $tableCoupons = app(Builder::class)->columns([
            ['data' => 'coupon_name', 'name' => 'coupon_name', 'title' => 'Name'],
            ['data' => 'discount', 'name' => 'discount', 'title' => 'Discount'],
            ['data' => 'applied_on', 'name' => 'applied_on', 'title' => 'Applied On'],
            ['data' => 'applied_at', 'name' => 'applied_at', 'title' => 'Applied At']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ])->ajax(route('tables.coupons') . '?user_id=' . $student->user_id)->setTableId('table-coupons');

        $rewardsEarned = JMoney::where('user_id', $student->user_id)->sum('points');
        $rewardsGained = Order::where('user_id', $student->user_id)->sum('reward_amount');

        return view('pages.students.show', compact('student', 'tableOrders', 'tableCart', 'tableCoupons', 'rewardsEarned', 'rewardsGained'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    public function getTableOrders()
    {
        if (request()->ajax()) {
            $query = Order::query();
            $query->where('user_id', request('user_id'));
            $query->latest();

            return DataTables::of($query)
                ->addColumn('associate', function($query) {
                    return $query->associate->user->name ?? null;
                })
                ->addColumn('payment_status', function($query) {
                    return $query->transaction_response_status;
                })
                ->addColumn('payment_mode', function($query) {
                    if ($query->payment_mode == 1) {
                        return 'Online';
                    }

                    if ($query->payment_mode == 2) {
                        return 'Cash On Delivery';
                    }

                    if ($query->payment_mode == 3) {
                        return 'Prepaid';
                    }
                })
                ->addColumn('status', function($query) {
                    if ($query->status == Order::STATUS_RECEIVED) {
                        return '<span class="badge bg-secondary">Received</span>';
                    }

                    if ($query->status == Order::STATUS_PROCESSED) {
                        return '<span class="badge bg-primary">Processed</span>';
                    }

                    if ($query->status == Order::STATUS_SHIPPED) {
                        return '<span class="badge bg-info">Shipped</span>';
                    }

                    if ($query->status == Order::STATUS_DELIVERED) {
                        return '<span class="badge bg-success">Delivered</span>';
                    }

                    if ($query->status == Order::STATUS_PENDING) {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                })
                ->addColumn('created_at', function($query) {
                    return $query->created_at->toDayDateTimeString();
                })
                ->rawColumns(['payment_status', 'status'])
                ->make(true);
        }
    }

    public function getTableCart()
    {
        if (request()->ajax()) {
            $query = Cart::query();
            $query->where('user_id', request('user_id'));
            $query->latest();

            return DataTables::of($query)
                ->addColumn('package_name', function($query) {
                    if ($query->package) {
                        return $query->package->name;
                    }
                })
                ->addColumn('price', function($query) {
                    if ($query->package) {
                        return $query->package->price;
                    }
                })
                ->addColumn('added_at', function($query) {
                    return $query->created_at->toDayDateTimeString();
                })
                ->make(true);
        }
    }

    public function getTableCoupons()
    {
        if (request()->ajax()) {
            $query = Order::query();
            $query->with('coupon');
            $query->where('user_id', request('user_id'));
            $query->whereHas('coupon');
            $query->latest();

            return DataTables::of($query)
                ->addColumn('coupon_name', function($query) {
                    return $query->coupon->name ?? null;
                })
                ->addColumn('discount', function($query) {
                    if ($query->coupon) {
                        if ($query->coupon->amount_type == Coupon::FLAT) {
                            return '₹' . $query->coupon->amount;
                        } else {
                            return $query->coupon->amount . '%';
                        }
                    }
                })
                ->addColumn('applied_on', function($query) {
                    if ($query->orderItems) {

                        $packages = '';

                        foreach ($query->orderItems as $id => $orderItem) {
                            $packages .= $orderItem->package->name;

                            if (count($query->orderItems) > 1 && $id != count($query->orderItems) - 1) {
                                $packages .= ' + ';
                            }
                        }

                        return $packages;
                    }
                })
                ->addColumn('applied_at', function($query) {
                    return $query->created_at->toDayDateTimeString() ?? null;
                })
                ->make(true);
        }
    }

    public function getBarDataByYear() {
        $startOfYear = Carbon::create(request('year'))->startOfYear();
        $endOfYear = Carbon::create(request('year'))->endOfYear();

        $signUpData = Student::whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $endOfYear)
            ->select(DB::raw('count(*) as count') , DB::raw('MONTH(created_at) as month'))
            ->groupBy('month')
            ->get();

        $orderData = Order::whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $endOfYear)
            ->select(DB::raw('count(*) as count') , DB::raw('MONTH(created_at) as month'))
            ->groupBy('month')
            ->get();

        $cartData = Cart::whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $endOfYear)
            ->select(DB::raw('count(*) as count') , DB::raw('MONTH(created_at) as month'))
            ->groupBy('month')
            ->get();

        $signUpBarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $signUpBarData[$month] = 0;
        }

        foreach($signUpData as $data) {
            $signUpBarData[$data->month] = $data->count;
        }

        $orderBarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $orderBarData[$month] = 0;
        }

        foreach($orderData as $data) {
            $orderBarData[$data->month] = $data->count;
        }

        $cartBarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $cartBarData[$month] = 0;
        }

        foreach($cartData as $data) {
            $cartBarData[$data->month] = $data->count;
        }

        $xAxisData = [1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MAY', 6 => 'JUN', 7 => 'JUL', 8 => 'AUG', 9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DEC'];

        return response()->json(['signUp' => ['data' => $signUpBarData, 'xAxis' => $xAxisData], 'order' => ['data' => $orderBarData, 'xAxis' => $xAxisData], 'cart' => ['data' => $cartBarData, 'xAxis' => $xAxisData]]);
    }

    public function getBarDataByMonth() {
        $MonthAndYear = request('month');
        $MonthAndYear = explode('-', $MonthAndYear);
        $month = $MonthAndYear[0];
        $year = $MonthAndYear[1];

        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $xAxisData = [];

        for ($xAxis = 1; $xAxis <= $daysInMonth; $xAxis++) {
            $xAxisData[$xAxis] = $xAxis;
        }

        $signUpData = Student::whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->select(DB::raw('count(*) as count') , DB::raw('DAY(created_at) as day'))
            ->groupBy('day')
            ->get();

        $orderData = Order::whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->select(DB::raw('count(*) as count') , DB::raw('DAY(created_at) as day'))
            ->groupBy('day')
            ->get();

        $cartData = Cart::whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->select(DB::raw('count(*) as count') , DB::raw('DAY(created_at) as day'))
            ->groupBy('day')
            ->get();

        $signUpBarData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $signUpBarData[$day] = 0;
        }

        foreach($signUpData as $data) {
            $signUpBarData[$data->day] = $data->count;
        }

        $orderBarData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $orderBarData[$day] = 0;
        }

        foreach($orderData as $data) {
            $orderBarData[$data->day] = $data->count;
        }

        $cartBarData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $cartBarData[$day] = 0;
        }

        foreach($cartData as $data) {
            $cartBarData[$data->day] = $data->count;
        }

        return response()->json(['signUp' => ['data' => $signUpBarData, 'xAxis' => $xAxisData], 'order' => ['data' => $orderBarData, 'xAxis' => $xAxisData], 'cart' => ['data' => $cartBarData, 'xAxis' => $xAxisData]]);
    }

    public function export()
    {
        $signUpDate = request()->input('export_sign_up_date') ?? '';

        if ($signUpDate) {
            $signUpDate = Carbon::parse($signUpDate);
        } else {
            $signUpDate = '';
        }

        return Excel::download(new StudentExport($signUpDate), 'STUDENTS_' . time() . '.csv');
    }

    public function getImport()
    {
        return view('pages.students.import.index');
    }

    public function postImport()
    {
        $filePath = null;

        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/imports', $fileName);
        }

        $importLog = ImportLog::create();

        if ($filePath) {
            Excel::import(new UsersImport, $filePath);
            Storage::delete($filePath);
        }

        $importLog = ImportLog::find($importLog->id);

        return back()->with('response', $importLog);
    }
}
