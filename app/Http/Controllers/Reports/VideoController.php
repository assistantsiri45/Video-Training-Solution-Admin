<?php

namespace App\Http\Controllers\Reports;

use App\Exports\VideoExport;
use App\Http\Controllers\Controller;
use App\Models\CustomizedPackage;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\SubjectPackage;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return View
     */
    public function index(Builder $builder)
    {
        $query = Video::query();

        if (request()->ajax()) {
            if (request()->filled('filter.search')) {
                $query->where('title', 'LIKE', '%' . request()->input('filter.search') . '%');
            }

            return DataTables::of($query)
                ->addColumn('packages', function($query) {
                    $chapterPackageIDs = PackageVideo::where('video_id', $query->id)
                        ->get()->pluck('package_id');

                    $chapterPackageIDs = Package::whereIn('id', $chapterPackageIDs)
                        ->where('type', Package::TYPE_CHAPTER_LEVEL)
                        ->get()->pluck('id');

                    $subjectPackageIDs = SubjectPackage::whereIn('chapter_package_id', $chapterPackageIDs)
                        ->get()->pluck('package_id');

                    $customizedPackageIDs = CustomizedPackage::whereIn('selected_package_id', $chapterPackageIDs)
                        ->orWhereIn('selected_package_id', $subjectPackageIDs)
                        ->get()->pluck('package_id');

                    $packages = Package::whereIn('id', $chapterPackageIDs)
                        ->orWhereIn('id', $subjectPackageIDs)
                        ->orWhereIn('id', $customizedPackageIDs)
                        ->get()->pluck('name');

                    if (count($packages) > 2) {
                        return implode("<br>", $packages->take(2)->toArray()) .
                            "<br><a class='a-modal-view-package' href='#modal-view-packages' data-toggle='modal'
                                    data-packages='$packages'>More</a>";
                    } else {
                        return implode("<br>", $packages->toArray());
                    }
                })
                ->addColumn('action', 'pages.reports.videos.action')
                ->rawColumns(['packages', 'action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'formatted_duration', 'name' => 'formatted_duration', 'title' => 'Duration'],
            ['data' => 'packages', 'name' => 'packages', 'title' => 'Packages'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
        ]);

        $draftVideoCount = Video::query()->where('is_published', false)->count();
        $publishedVideoCount = Video::query()->where('is_published', true)->count();

        return view('pages.reports.videos.index', compact('html', 'draftVideoCount', 'publishedVideoCount'));
    }

    public function export()
    {
        $search = request()->input('export_search') ?? '';

        return Excel::download(new VideoExport($search), 'VIDEOS_' . time() . '.csv');
    }
}
