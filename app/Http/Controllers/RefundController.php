<?php

namespace App\Http\Controllers;

use App\Mail\RefundMail;
use App\Models\JMoney;
use App\Models\JMoneySetting;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Order::with('student','payment')
                            ->where('payment_status',1)
                            ->where('is_cseet',0)
                            ->has('payment');

            if (request()->filled('filter.date')) {
                $query->whereDate('created_at', Carbon::create(request()->input('filter.date')));
            }
            return DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query
                                ->where('id',request('filter.search'))
                                ->orWhereHas('payment',function ($query){
                                    $query->where('receipt_no',request('filter.search'));
                                })
                                ->orWhereHas('student',function ($query){
                                    $query->where('name','like', "%" . request('filter.search') . "%")
                                          ->orWhere('phone','like',"%". request('filter.search') . "%");
                                });
                        });
                    }

                })
                ->editColumn('created_at',function ($query)
                {
                    if($query->created_at)
                    {
                        return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d-m-y H:i:s');
                    }
                })
                ->addColumn('action', 'pages.refunds.action')
                ->editColumn('email', function($query)
                {
                    return '<div><input class="form-control" id="email-'.$query->id.'" type="hidden" name="package" value="' . $query->student->email . '"><input class="form-control" id="name-'.$query->id.'" type="hidden" name="package" value="' . $query->student->name . '">'.$query->student->email.'</div>';

                })
                ->rawColumns(['action','email'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Order#'],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Student', 'defaultContent' => ''],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email','defaultContent' => ''],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone','defaultContent' => ''],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'payment.receipt_no', 'name' => 'payment.receipt_no', 'title' => 'Invoice#','defaultContent' => ''],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, ]
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'lengthChange' => false,
            'bInfo' => false
        ])->orderBy(0, 'desc');

        return view('pages.refunds.index', compact('html'));
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
        //
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

        $orders = Order::findOrFail($id);
        $orders->is_refunded = 1;
        $orders->save();
        $jmoneysettings = JMoneySetting::first();
        $jmoney = new JMoney();
        $jmoney->user_id = $orders->user_id;
        $jmoney->activity = 5;
        $jmoney->points = $orders->net_amount;
        $jmoney->expire_after = $jmoneysettings->refund_expiry;
        $jmoney->expire_at = Carbon::now()->addDays($jmoney->expire_after);
        $jmoney->save();
        $email_to=$request->email;
        $user_details = [
            'email' => $request->email,
            'name' => $request->name,
        ];

        try{
            Mail::send(new RefundMail($user_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

        return response()->json('true');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
