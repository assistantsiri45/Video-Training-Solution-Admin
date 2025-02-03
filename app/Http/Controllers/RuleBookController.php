<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageRulebook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class RuleBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = PackageRulebook::with('package')->orderBy('created_at','desc');


        if (request()->ajax()) {
            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function($query) {
                            $query->where('title', 'like', '%' . request()->input('filter.search') . '%');
                        });
                    }

                })
                
                ->editColumn('package.name', function($query) {
                    if ($query->package) {
                        return $query->package->name;
                    }
                    return '-';
                })
                ->addColumn('file_name', function ($query) {
                    if ($query->s3_url) {
                        return '<a target="_blank" href="' . $query->s3_url . '">' . $query->s3_url . '</a>';
                    } else {
                        return '-';
                    }
                })
                
                ->editColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })

                ->addColumn('action', function ($rulebook) {
                    return view('pages.rulebook.action', compact('rulebook'));
                })
                ->rawColumns(['file_name','action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'package.name', 'name' => 'package.name', 'title' => ' Package Type', 'defaultContent' => ''],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload', 'defaultContent' => ''],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
            'stateSave'=> false,
        ]);

        return view('pages.rulebook.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.rulebook.s3create');
    }

    public function searchPackages(Request $request)
    {
        $searchTerm = $request->get('q');
        $packages = Package::where('name', 'like', '%' . $searchTerm . '%')->get(['id', 'name']);
        return response()->json($packages);
    }

    public function checkPackageRulebookExists(Request $request)
    {
        $packageId = $request->get('package_id');
        $exists = PackageRulebook::where('package_id', $packageId)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function uploadS3(Request $request){
        
        if ($request->hasFile('file')) {
            
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filesize = $file->getSize();
            $extension = $file->getClientOriginalExtension();
            $extension = strtolower($extension);
            
            //clearning filename
            $filename = pathinfo($filename, PATHINFO_FILENAME);
            $filename = preg_replace('/\s+/', ' ', $filename); // convert extra spaces to single space
            $filename = str_replace(' ', '_', trim($filename)); // converting spaces to underscore
            $filename = $filename . '.' . $extension;   // final filename with extension lowercase

            $uploadPath = '/rulebook';
            
            $this->validate($request, [
                'file' => 'required|mimes:pdf', // maximum file size of 10MB
            ]);
                
            // Upload the file to S3 without setting an ACL
            $path = Storage::disk('s3')->putFileAs($uploadPath, $file, $filename);

            // Generate the public URL of the uploaded file
            // $url = Storage::disk('s3')->url($path);
            $url =  $path;

            // Return the public URL to the uploaded file
            return response()->json(['success' => 'File uploaded successfully.', 'url' => $url]);
        } else {
            return response()->json(['error' => 'No file uploaded.']);
        }
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'package_id' => 'required',
            'rulebook' => 'required',
            'title' => 'required',
        ]);
        
        $s3_url = env('AWS_CDN') . $request->input('rulebook');
        
        try {
            DB::beginTransaction();
            // CODE TO SAVE RULEBOOK HERE
            $data = [
                'package_id' =>$request->input('package_id'),
                'title' =>$request->input('title'),
                's3_url' => $s3_url,
            ];

            PackageRulebook::create($data);
            DB::commit();
        } catch (\Exception $e) {
            info($e->getMessage());
            return redirect()->back()->withInput()->withError($e->getMessage());
        }

        return redirect()->route('rule-book.index')->with('success', 'Rulebook uploaded successfully');
    }

    public function show(PackageRulebook $rulebook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(PackageRulebook $rulebook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($rulebook)
    {
        // Delete the rulebook
        $rulebook = PackageRulebook::findOrFail($rulebook);
        $rulebook->delete();

        // Optionally, you can add a success message and redirect the user
        return redirect()->route('rule-book.index')->with('success', 'Rulebook deleted successfully!');
    }

}