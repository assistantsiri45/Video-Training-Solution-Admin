<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class BlogTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = BlogTag::query();

            return DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query
                                ->where('name','like', "%" . request('filter.search') . "%");
                        });
                    }
                })
                ->addColumn('action','pages.blogs.blog_tags.action')
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
            ])
            ->parameters([
                'searching' => false,
                'ordering' => false,
            ])
            ->orderBy(0, 'desc');

        return view('pages.blogs.blog_tags.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.blogs.blog_tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|alpha_spaces|unique:blog_tags',
        ]);
        $tag = new BlogTag();
        $tag->name = $request->input('name');
        $tag->save();
        return redirect()->route('blogs.tags.index')->with('success','Successfully Created');
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
        $tag = BlogTag::findOrFail($id);

        return view('pages.blogs.blog_tags.edit',compact('tag'));
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
        $validated = $request->validate([
            'name' => 'alpha_spaces|unique:blog_tags,name,' .$id,
        ]);
        $tag = BlogTag::findOrFail($id);
        $tag->name = $request->input('name');
        $tag->update();
        return redirect()->route('blogs.tags.index')->with('success','Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = BlogTag::findOrFail($id);

        $tag->delete();

        return response()->json(true, 200);
    }
}
