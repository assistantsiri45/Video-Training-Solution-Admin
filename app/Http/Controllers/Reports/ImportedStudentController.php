<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ImportedStudentsExport;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class ImportedStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
//        $query = Student::query()->with('user','orders.orderItems.package')->whereHas('user',function ($query){
//            $query->where('is_imported',1);
//        })
//            ->whereHas('orders',function ($query){
//                $query->where('is_imported_user',1);
//            })->get();


        if (request()->ajax()) {
            $query = Student::query()->with('user','orderItems.package')->whereHas('user',function ($query){
                $query->where('is_imported',1);
                });


            $query->orderBy('name', 'asc');

            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('search')) {
                        $query->where(function ($query) {
                            $query->whereHas('user', function ($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                                    ->orWhere('email', 'like', '%' . request()->input('filter.search') . '%')
                                    ->orWhere('phone', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                    }

                    if (!empty(request('filter.sign_up_date'))) {
                        $query->whereDate('created_at', Carbon::parse(request('filter.sign_up_date')));
                    }
                })
                ->addColumn('action', 'pages.reports.imported_students.action')
                ->rawColumns(['action','name'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.reports.imported_students.index', compact('html'));
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

    public function export()
    {
        $search = request()->input('export_search') ?? '';
        $export_date = request()->input('export_sign_up_date') ?? '';

        if ($export_date) {
            $export_date = Carbon::parse($export_date);
        } else {
            $export_date = '';
        }

        return Excel::download(new ImportedStudentsExport($export_date,$search), 'STUDENTS_' . time() . '.csv');
    }
}
