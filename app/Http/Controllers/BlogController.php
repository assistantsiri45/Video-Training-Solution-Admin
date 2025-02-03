<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder$builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Blog::query()->orderBy('order');

            return DataTables::of($query)
                ->editColumn('is_published', function ($query) {
                    if ($query->is_published) {
                        return '<i class="fas fa-check"></i>';
                    }
                    else{
                        return '<i class="fas fa-times"></i>';
                    }

                    return '<i class="fas fa-times"></i>';
                })
                ->editColumn('published_at', function ($query) {
                    if ($query->published_at){
                        return optional($query->published_at)->toFormattedDateString();
                    }
                    else{
                        return '-';
                    }

                })
                ->editColumn('publisher', function ($query) {
                    if ($query->publisher){
                        return optional($query->publisher)->name;
                    }
                    else{
                        return '-';
                    }

                })
                ->editColumn('created_at',function ($query){
                    if($query->created_at){
                        return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d-m-Y');
                    }
                })
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="blog-id" value="' . $query->id . '"></div>';
                })
                ->addColumn('action', 'pages.blogs.action')
                ->rawColumns(['is_published', 'order', 'action'])
                ->make(true);
        }

        $tableBlogs = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
            ['data' => 'author', 'name' => 'author', 'title' => 'Author'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Published?'],
            ['data' => 'published_at', 'name' => 'published_at', 'title' => 'Published At'],
            ['data' => 'publisher', 'name' => 'publisher', 'title' => 'Publisher'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => false,
            'ordering' => false
        ]);

        return view('pages.blogs.index', compact('tableBlogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $query = Blog::query();
        $query->ofPublished();
        $blogs = $query->get();

        $query = BlogTag::query();
        $tags = $query->get();

        $query = BlogCategory::query();
        $categories = $query->get();

        return view('pages.blogs.create', compact('blogs', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug,NULL,id,deleted_at,NULL',
            'author' => 'required',
            'image' => 'required'
        ]);
        DB::beginTransaction();

        if(!$request->image)
        {
            return false;
        }
        $photo = $request->image;

        list($type, $photo) = explode(';', $photo);
        list(, $photo)      = explode(',', $photo);
        $photo = base64_decode($photo);
        $image_name = time().'.png';
        Storage::disk('public')->put("blogs/images/$image_name", $photo);
        $blog = new Blog();
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
//        $blog->category_id = $request->input('category');
        $blog->author = $request->input('author');
        $blog->body = $request->input('body');
        $blog->image = $image_name;
        $blog->order = Blog::query()->count() + 1;
        $blog->save();

        $relatedBogIDs = $request->input('related_blogs');

        if ($relatedBogIDs) {
            $blog->relatedBlogsSync()->attach($relatedBogIDs);
        }

        $tagIDs = $request->input('tags');

        if ($tagIDs) {
            $blog->blogTagsSync()->attach($tagIDs);
        }

        DB::commit();

        return redirect(route('blogs.index'))->with('success', 'Blog successfully stored');
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
        $blog = Blog::findOrFail($id);

        $query = Blog::query();
        $query->whereNotIn('id', [$id]);
        $query->ofPublished();
        $relatedBlogs = $query->get();

        $query = Blog::query();
        $query->whereHas('relatedBlogs', function ($query) use ($id) {
            $query->where('blog_id', $id);
        });
        $relatedBlogIDs = $query->pluck('id');

        $query = BlogTag::query();
        $tags = $query->get();

        $query = BlogTag::query();
        $query->whereHas('blogTags', function ($query) use ($id) {
            $query->where('blog_id', $id);
        });
        $blogTagIDs = $query->pluck('id');

        $query = BlogCategory::query();
        $categories = $query->get();

        $file_path=url('storage/blogs/images/'.$blog->image);

        return view('pages.blogs.edit', compact('blog', 'relatedBlogs', 'categories', 'relatedBlogIDs', 'tags', 'blogTagIDs','file_path'));
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
        $request->validate([
            'title' => 'required',
            'slug' => "required|unique:blogs,slug,$id,id,deleted_at,NULL",
            'author' => 'required'
        ]);

        $blog = Blog::findOrFail($id);
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
//        $blog->category_id = $request->input('category');
        $blog->author = $request->input('author');
        $blog->body = $request->input('body');

        if($request->image)
        {
            $photo = $request->image;
            list($type, $photo) = explode(';', $photo);
            list(, $photo)      = explode(',', $photo);
            $photo = base64_decode($photo);
            $image_name= time().'.png';
            Storage::disk('public')->put("blogs/images/$image_name", $photo);
            $blog->image=$image_name;
        }

        $blog->update();

        $relatedBogIDs = $request->input('related_blogs');

        $blog->relatedBlogsSync()->sync($relatedBogIDs ?? []);

        $blogTagIDs = $request->input('tags');

        $blog->blogTagsSync()->sync($blogTagIDs ?? []);

        return redirect(route('blogs.index'))->with('success', 'Blog successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json(['success' => 'Blog successfully deleted']);
    }

    public function uploadImage()
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $fileName = 'IMAGE_' . time() . '.' .$image->getClientOriginalExtension();
            $image->storeAs('public/blogs/body_images/', $fileName);

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => url('storage/blogs/body_images') .'/' . $fileName
                ]
            ]);
        }

        return response()->json([
            'success' => 0
        ]);
    }

    public function publish($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->publish();

        return response()->json(['success' => 'Blog successfully published']);
    }

    public function changeOrder()
    {
        $blogIDs = request()->input('blogs');

        info($blogIDs);

        if ($blogIDs) {
            $index = 1;

            foreach ($blogIDs as $blogID) {
                $blog = Blog::find($blogID);

                if ($blog) {
                    $blog->order = $index;
                    $blog->save();
                }

                $index++;
            }
        }
    }

    public function preview($id)
    {
        $blog = Blog::with('category')->findOrFail($id);
        $body = json_decode($blog->body, true);
        $blocks = $body['blocks'];

        $blog->body = collect($blocks)->map(function ($block) {
            switch ($block['type']) {
                case 'header':
                    $level = $block['data']['level'] ?? 1;
                    return '<h'.$level.'>'.$block['data']['text'].'</h'.$level.'>';
                case 'paragraph':
                    return '<p>'.$block['data']['text'].'</p>';
                case 'image':
                    $classes = [
                        'border' => $block['data']['withBorder'],
                        'bg-light' => $block['data']['withBackground'],
                        'justify-content-center' => $block['data']['withBackground'],
                        'p-2' => $block['data']['withBackground'],
                    ];

                    $classes = collect($classes)->filter()->keys()->join(' ');

                    $img_Classes = [
                        'w-100' => $block['data']['stretched'],
                    ];

                    $img_Classes = collect($img_Classes)->filter()->keys()->join(' ');

                    $caption = $block['data']['caption'];

                    $html = '';

                    if ($block['data']['file']) {
                        if ($caption) {
                            $html =  '<div class="d-flex '.$classes.'"><img class="img-fluid '.$img_Classes.'" src="'.$block['data']['file']['url'].'"  alt="'.$caption.'" /></div>';
                        } else {
                            $html =  '<div class="d-flex '.$classes.'"><img class="img-fluid mb-5'.$img_Classes.'" src="'.$block['data']['file']['url'].'"  alt="'.$caption.'" /></div>';
                        }
                    }

                    if ($caption) {
                        $html .= "<small class='d-block text-center text-muted mb-5'>$caption</small>";
                    }

                    return $html;
            }
        })->join('');

        return response()->json($blog);
    }
}
