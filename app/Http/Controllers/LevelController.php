<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Level::query()->with('course')->whereHas('course')->orderBy('order');
        $courses = Course::orderBy('name')->get();
        if (request()->ajax()) {
            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query->where('name','like','%'. request()->input('filter.search') .'%')
                        ->orWhere(function ($query) {
                            $query->where(function ($query) {
                                $query->whereHas('course', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                });
                            });
                        });
                });
            }

            if (request()->filled('filter.course')) {
                $query->where(function ($query) {
                    $query->where('course_id', request()->input('filter.course'));
                });
            }
            return DataTables::of($query)
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('id', $order);
                })
                ->orderColumn('course.name', function ($query, $order) {
                    $query->orderBy('course.id', $order);
                })
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="level-id" value="' . $query->id . '"></div>';
                })
                ->addColumn('action', 'pages.levels.action')
                ->rawColumns(['action', 'order'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Level', 'width' => '45%'],
            ['data' => 'course.name', 'name' => 'name', 'title' => 'Course', 'width' => '45%'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.levels.index', compact('html','courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.levels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'course_id' => 'required',
        ]);

        $level = new Level();

        $level->name = $request->input('name');
        $level->course_id = $request->input('course_id');
        $level->display = $request->input('display');

        $level->save();

        return redirect(route('levels.create'))->with('success', 'Level successfully created');
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
        $level = Level::findOrFail($id);

        return view('pages.levels.edit', compact('level'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'course_id' => 'required',
        ])->validate();

        $level = Level::findOrFail($id);

        $level->name = $request->name;
        $level->course_id = $request->course_id;
        $level->display = $request->input('display');

        $level->save();

        return redirect(route('levels.index'))->with('success', 'Level successfully updated');;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $level = Level::findOrFail($id);

        // $level->delete();

        // return response()->json(true, 200);
         /***************TE Modified***********/
         if($level->is_enabled==true){           
            $level->is_enabled= false;               
        }
    else{
        $level->is_enabled=true;
    }
    $level->save();
    return response()->json(true, 200);

    /***************TE ends*************/
    }

    public function changeOrder()
    {
        $levelIDs = request()->input('levels');

        if ($levelIDs) {
            $index = 1;

            foreach ($levelIDs as $levelID) {
                $level = Level::find($levelID);

                if ($level) {
                    $level->order = $index;
                    $level->save();
                }

                $index++;
            }
        }
    }

}
