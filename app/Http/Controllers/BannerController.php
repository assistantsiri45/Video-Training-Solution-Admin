<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class BannerController extends Controller
{
    public function index(Builder $builder)
    {
            if (request()->ajax()) {
            $query = Banner::with('user');

            $query->orderBy('order');

            return DataTables::of($query)
                ->orderColumn('id', 'id $1')
                ->editColumn('image', function($query) {
                    if ($query->image) {
                        return '<span><img src="'. $query->image . '" class="rounded-square" width="100" height="60"></span>';
                    }
                    return '';
                })
                ->editColumn('title_url', function($query) {
                    if ($query->title_url) {
                        return '<a target="_blank" href="'.$query->title_url.'">'.$query->title_url.'</a>';
                    }
                })
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="banner-id" value="' . $query->id . '"></div>';
                })
                ->addColumn('action', 'pages.banners.action')
                ->rawColumns(['action','image','title_url', 'order'])
                ->make(true);
            }

        $html = $builder->columns([
            ['data' => 'image', 'name' => 'image', 'title' => 'Image'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'title_url', 'name' => 'title_url', 'title' => 'URL'],
            ['data' => 'youtube_id', 'name' => 'youtube', 'title' => 'Youtube', 'orderable' => false],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Created By'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '80px']
        ]);

        return view('pages.banners.index', compact('html'));
    }

    public function create()
    {
        return view('pages.banners.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request,[
            'title' => 'alpha_spaces|nullable',
            'link' => 'url|nullable',
            'youtube_id' => 'regex:/^([^<>]*)$/|nullable',
            'file' => 'required|mimes:jpeg,png,jpg,gif|max:3000',
        ]);
        //dd($request->file);
        $banner =  new Banner();
        $banner->user_id = Auth::id();
        $banner->title = $request->title;
        $banner->alt = $request->alt;
        $banner->title_url = $request->link;
        $banner->youtube_id = $request->youtube_id;
        $extension = $request->file->extension();
        if($extension!='gif'){
            // if(!$request->image){
            //     return false;
            // }
            // $data = $request->image;
            // list($type, $data) = explode(';', $data);
            // list(, $data)      = explode(',', $data);
            // $data = base64_decode($data);
            // $image_name= time().'.png';
            // Storage::disk('public')->put("banners/$image_name", $data);
            // $banner->image = $image_name;
            $path =  public_path() . '/storage/banners/';
            $categoryFile = $request->file('file');
            $mimeType = time().'.png';
            $categoryFile->move($path, $mimeType);
            $banner->image = $mimeType; 
        }else{
            
            $path =  public_path() . '/storage/banners/';
            $categoryFile = $request->file('file');
            $mimeType = time().'.gif';
            $categoryFile->move($path, $mimeType);
            $banner->image = $mimeType; 
        }
        $count = Banner::all()->count();
        //dd($count);
        $banner->order = $count+1;
        $banner->save();
        


        return redirect(url('banners'))->with('success', 'Banner successfully created');
    }

    public function show(Banner $banner)
    {

    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('pages.banners.edit',compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'alpha_spaces|nullable',
            'link' => 'url|nullable',
            'youtube_id' => 'regex:/^([^<>]*)$/|nullable',
            'file' => 'mimes:jpeg,png,jpg,gif|max:10000',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->user_id = Auth::id();
        $banner->title = $request->title;
        $banner->alt = $request->alt;
        $banner->title_url = $request->link;
        $banner->youtube_id = $request->youtube_id;
        $extension ='';
        if($request->file){
            $extension = $request->file->extension();
        
        if($extension!='gif'){
            // if($request->image){
            //     $data = $request->image;
            //     list($type, $data) = explode(';', $data);
            //     list(, $data)      = explode(',', $data);
            //     $data = base64_decode($data);
            //     $image_name= time().'.png';
            //     Storage::disk('public')->put("banners/$image_name", $data);
            //     $banner->image = $image_name;
            // }
            $path =  public_path() . '/storage/banners/';
            $categoryFile = $request->file('file');
            $mimeType = time().'.png';
            $categoryFile->move($path, $mimeType);
            $banner->image = $mimeType;
        }else{
            $path =  public_path() . '/storage/banners/';
            $categoryFile = $request->file('file');
            $mimeType = time().'.gif';
            $categoryFile->move($path, $mimeType);
            $banner->image = $mimeType;
        }
    }
        $banner->update();
        return redirect(url('banners'))->with('success', 'Banner successfully updated');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        $banner->delete();
        $banners=Banner::orderBy('order')->get();
        $index=1;
        foreach($banners as $baner){
            $baner->order=$index;
            $baner->save();
            $index++;
        }
        
        return response()->json(true, 200);
    }

    public function changeOrder()
    {
        $bannerIDs = request()->input('banners');
        if ($bannerIDs) {
            $index = 1;

            foreach ($bannerIDs as $bannerID) {
                $banner = Banner::find($bannerID);

                if ($banner) {
                    $banner->order = $index;
                    $banner->save();
                }

                $index++;
            }
        }
    }
}
