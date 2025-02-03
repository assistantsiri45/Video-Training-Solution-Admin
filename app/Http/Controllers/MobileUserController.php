<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Models\ImportLog;
use App\Imports\UsersImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class MobileUserController extends Controller
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
            $query = User::query();
            $query->where('is_imported',3);
            $query->where('is_verified',1);
            //$query->orderBy('name', 'asc');
           

            return DataTables::of($query)
                ->filter(function($query) {
                    if (!empty(request('filter.sign_up_date'))) {
                        $query->whereDate('created_at', Carbon::parse(request('filter.sign_up_date')));
                    }
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
                ->editColumn('name', function($query) {
                    if(!empty($query->name)){
                        return $query->name;
                    }else{
                        return '-';
                    }
                })
                ->editColumn('email', function($query) {
                    if(!empty($query->email)){
                        return $query->email;
                    }else{
                        return '-';
                    }
                })
                ->editColumn('phone', function($query) {
                    if(!empty($query->phone)){
                        return $query->phone;
                    }else{
                        return '-';
                    }
                })
                ->editColumn('created_at', function($query) {
                    if(!empty($query->created_at)){
                        return $query->created_at->toDayDateTimeString();
                    }else{
                        return '-';
                    }
                })
                ->editColumn('is_verify_email', function($query) {
                    if ($query->is_verify_email) {
                        return '<i class="fas fa-check"></i>';
                    }
                    else {
                        return '<i class="fas fa-times"></i>';
                    }
                })
                ->editColumn('is_verify_phone', function($query) {
                    if ($query->is_verify_phone) {
                        return '<i class="fas fa-check"></i>';
                    }
                    else {
                        return '<i class="fas fa-times"></i>';
                    }
                })
                ->rawColumns(['is_verify_email', 'is_verify_phone'])
                ->make(true);
                
               
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'User Id','orderable' => true],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name','orderable' => false],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email','orderable' => false],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone','orderable' => false],
            ['data' => 'is_verify_phone', 'name' => 'is_verify_phone', 'title' => 'Is Mobile Verified','orderable' => false],
            ['data' => 'is_verify_email', 'name' => 'is_verify_email', 'title' => 'Is Email Verified','orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Sign-Up','orderable' => true]
           
        ])->parameters([
            'searching' => false,
            //'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
            'stateSave'=> true,
        ]);

        return view('pages.mobileusers.index', compact('html'));
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
       //
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

   
    
}
