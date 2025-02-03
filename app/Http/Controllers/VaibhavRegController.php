<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\VaibhavScholarRegistration;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class VaibhavRegController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = VaibhavScholarRegistration::where('is_verified',1);
            if (request()->filled('filter.search')) {
               
                           
                                $query->where('student_name', 'like', '%' . request()->input('filter.search') . '%');
                                $query->orWhere('email_id', 'like', '%' . request()->input('filter.search') . '%');
                                $query->orWhere('junior_college', 'like', '%' . request()->input('filter.search') . '%');
                                $query->orWhere('student_contact_no',  request()->input('filter.search'));
                                $query->orWhere('parent_contact_no',  request()->input('filter.search'));
                           
                     
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
           
            return DataTables::of($query)

            ->addColumn('junior_college_address', function($query) {
                if($query->junior_college_address){
                    return $query->junior_college_address;
                }else{
                    return '-';
                }                
            })

            ->addColumn('created_at', function($query) {
                return optional($query->created_at)->format('d/m/y H:i:s');
            })
          ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'student_name', 'name' => 'student_name', 'title' => 'Name', 'orderable' => true],
            ['data' => 'student_contact_no', 'name' => 'student_contact_no', 'title' => 'Contact Number', 'orderable' => true],
            ['data' => 'parent_contact_no', 'name' => 'parent_contact_no', 'title' => 'parent Contact Number', 'orderable' => true],
            ['data' => 'email_id', 'name' => 'email_id', 'title' => 'Email Id', 'orderable' => true],
            ['data' => 'address', 'name' => 'address', 'title' => 'Address', 'orderable' => true],
            ['data' => 'city', 'name' => 'city', 'title' => 'City', 'orderable' => true],
            ['data' => 'pincode', 'name' => 'pincode', 'title' => 'Pincode', 'orderable' => true],
            ['data' => 'aggr_per_tenth', 'name' => '	aggr_per_tenth', 'title' => ' Agg % in Class X','orderable' => true],
            ['data' => 'aggr_per_eleventh', 'name' => 'aggr_per_eleventh', 'title' => 'Agg % in Class XI','orderable' => true],
           
            ['data' => 'junior_college', 'name' => 'junior_college', 'title' => 'Junior college', 'orderable' => false,'defaultContent' => ''],
            ['data' => 'junior_college_address', 'name' => 'junior_college_address', 'title' => 'Junior college address', 'orderable' => false,'defaultContent' => ''],
          //  ['data' => 'income', 'name' => 'income', 'title' => 'Income', 'orderable' => true],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date & Time', 'orderable' => true],
            // ['data' => 'logout_time', 'name' => 'logout_time', 'title' => 'Logout Time', 'orderable' => true],
          
            // ['data' => 'ip_address', 'name' => 'ip_address', 'title' => 'IP Address', 'orderable' => true],
            //  ['data' => 'response', 'name' => 'response', 'title' => '', 'orderable' => false]
            
           
        ])
        ->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 75,
        ])->orderBy(0,'desc');

        return view('pages.vaibhav_registration.index', compact('table'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    
}
