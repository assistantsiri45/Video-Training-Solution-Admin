<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;
class EmailLogController extends Controller
{
    public function index(Builder $builder){
        if (request()->ajax()) {
            $query = EmailLog::query();
            
            return DataTables::of($query)
            ->filter(function($query) {
                if (!empty(request('filter.search'))) {                  
                   
                        $query->orwhere('email_to','LIKE', '%'.request('filter.search').'%')
                            ->orWhere('email_from','LIKE', '%'. request('filter.search').'%')
                            ->orWhere('content','LIKE', '%'. request('filter.search').'%');
                            
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
            ->make(true);
        }
        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'email_to', 'name' => 'email_to', 'title' => 'Email Sent to' ,'orderable' => false],
            ['data' => 'email_from', 'name' => 'email_from', 'title' => 'From Email ID', 'orderable' => false],
            ['data' => 'content', 'name' => 'content', 'title' => 'Content', 'orderable' => false],
            ['data' => 'created_at', 'name' => 'email_logs.created_at', 'title' => 'Date', 'orderable' => true],
           
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 75,
        ])->orderBy(0, 'desc');

        return view('pages.email-log.index', compact('table'));
    }
}
