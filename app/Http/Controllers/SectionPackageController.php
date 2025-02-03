<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SectionPackage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (request()->ajax()) {
            $query = SectionPackage::query()->with('package','section')->where('section_id', $id)->orderBy('order');

            $dataTable = DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where(function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                                        ->orWhere(function ($query) {
                                            $query->where(function ($query) {
                                                $query->whereHas('course', function ($query) {
                                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                                })->orWhereHas('level', function ($query) {
                                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                                })->orWhereHas('subject', function ($query) {
                                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                                })->orWhereHas('chapter', function ($query) {
                                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                                });
                                            });
                                        });
                                });
                            });
                        });
                    }

                    if (request()->filled('filter.course')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('course_id', request()->input('filter.course'));
                            });
                        });
                    }

                    if (request()->filled('filter.level')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('level_id', request()->input('filter.level'));
                            });
                        });
                    }

                    if (request()->filled('filter.subject')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('subject_id', request()->input('filter.subject'));
                            });
                        });
                    }

                    if (request()->filled('filter.chapter')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('chapter_id', request()->input('filter.chapter'));
                            });
                        });
                    }

                    if (request()->filled('filter.language')) {
                        $query->whereHas('package', function ($query) {
                            $query->where('language_id', request()->input('filter.language'));
                        });
                    }

                    if (request()->filled('filter.professor')) {
                        $query->whereHas('package', function ($query) {
                            $query->whereHas('chapterVideos', function ($query) {
                                $query->where('professor_id', request()->input('filter.professor'));
                            });
                        });
                    }
                })
                ->editColumn('duration',function ($query){
                    if($query->package->total_duration){
                        return gmdate("H:i:s",$query->package->total_duration);
                    }
                    else{
                        return '-';
                    }
                })
//                ->addColumn('action', function($query) {
//                    return '<a href="' . url('packages/videos') . '?package_id=' . $query->package_id . '"><i class="fas fa-video"></i></a>';
//                })
                ->addColumn('action', 'pages.sections.packages.action')

                ->rawColumns(['action']);

            return $dataTable->make(true);
        }
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
        $sectionPackage = SectionPackage::findOrFail($id);
        $sectionPackage->delete();
        return response()->json(true, 200);
    }
}
