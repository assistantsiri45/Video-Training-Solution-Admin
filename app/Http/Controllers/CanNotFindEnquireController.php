<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\CseetStudentDoc;
use App\Models\EmailSupport;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class CanNotFindEnquireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = EmailSupport::query();
            
            return DataTables::of($query)
            ->filter(function($query) {
                if (!empty(request('filter.search'))) {
                  
                   
                        $query->orwhere('first_name','LIKE', '%'.request('filter.search').'%')
                            ->orWhere('last_name','LIKE', '%'. request('filter.search').'%')
                            ->orWhere('phone','LIKE', '%'. request('filter.search').'%')
                            ->orWhere('email','LIKE', '%'. request('filter.search').'%')
                            ->orWhereDate('created_at','LIKE', '%'. request('filter.search').'%');
                            
                }
                if (request()->filled('filter.date')) {
                    $dateRange = request()->input('filter.date');
                    $explodedDates = explode(' - ', $dateRange);
                    $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                    $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                    $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                    $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';
                    $query->whereBetween('created_at', [$from, $to]);
                }
        })
            ->addColumn('created_at', function($query) {
                if(!empty($query->created_at)){
                    $datetime = explode(" ",$query->created_at);
                    $date = date("d-m-Y", strtotime($datetime[0]));
                    $date_time = $date.' '.$datetime[1];
                    return $date_time;
                }else{
                    return '-';
                }
                
            })
            // ->addColumn('time', function($query) {
            //     if(!empty($query->created_at)){
            //         $datetime = explode(" ",$query->created_at);
            //         // $date = date("d-m-Y", strtotime($datetime[0]));
            //         $date_time = $datetime[1];
            //         return $date_time;
            //     }else{
            //         return '-';
            //     }
                
            // })
            ->addColumn('query', function($query) {
                $text=strip_tags($query->query);
                if(strlen($text)> 22){
                    $limit=22;
                    $truncatedtext=substr($text, 0, $limit);
                    return $truncatedtext.'.....<a class="a-response" data-id=' . $query->id  . '><i class="fas">Read More</i></a>';
                    
                }else{
                    
                    return $text;
                    
                }
               
                // if (str_word_count($text, 0) > $limit) {
                //     $words = str_word_count($text, 2);
                //     $pos   = array_keys($words);
                //     $text  = substr($text, 0, $pos[$limit]) . '...';
                // }
                // $truncatedtext=substr($text, 0, $limit);
                // return $truncatedtext.'.....<a class="a-response" data-id=' . $query->id  . '><i class="fas">Read More</i></a>';
                
                
            })
            // ->addColumn('response', function($query) {
                    
            //     return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                  
            // })
            ->rawColumns(['query'])
                
            ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID','defaultContent' => '','width' => '2%'],
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name','defaultContent' => '','width' => '13%'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Last Name', 'defaultContent' => '','width' => '13%'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'defaultContent' => '','width' => '8%'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone', 'defaultContent' => '','width' => '5%'],
            ['data' => 'query', 'name' => 'query', 'title' => 'Query', 'defaultContent' => '', 'searchable' => false,'width' => '20%'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date & Time', 'searchable' => true, 'orderable' => true,'width' => '32%'],
            // ['data' => 'time', 'name' => 'time', 'title' => 'Time', 'searchable' => true, 'orderable' => true,'width' => '16%']
        ])
          ->parameters([
            'searching' => true,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => true,
            'StateSave'=>true,
            'bInfo' => true,
            'pageLength'=> 25,
        ])->orderBy(0,'desc');

        return view('pages.cannotfindenquire.index', compact('html'));
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
        $query = EmailSupport::find($id);
        return $query->query;
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
        //
    }
}
