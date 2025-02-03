<?php

namespace App\Http\Controllers\API;

use App\Exports\PackagesExport;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\SubjectPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PackageController extends Controller
{
    public function group()
    {
        $packageID = request()->input('package_id');
        $isSortable = 'sortable-tbody';

        if (request()->filled('is_sortable')) {
            $isSortable = request()->input('is_sortable') == 'true' ? 'sortable-tbody' : '';
        }

        $chapterPackageIDs = SubjectPackage::where('package_id', $packageID)->get()->pluck('chapter_package_id')->toArray();

        $selectedPackages = request()->input('packages');

        if (! $selectedPackages) {
            return null;
        }

        $allChapterPackageIDs = array_unique (array_merge ($chapterPackageIDs, $selectedPackages));

        $implodedIDs = implode(',', $allChapterPackageIDs);

        $packages = Package::whereIn('id', $allChapterPackageIDs)->orderByRaw(DB::raw("FIELD(id, $implodedIDs)"))->get();

        $response = '';

        $response .=
            '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-left">PACKAGE</th>
                        <th scope="col" class="text-right">ORDER</th>
                    </tr>
                </thead>
                <tbody class="' . $isSortable . '">';
            foreach ($packages as $index => $package) {
                $response .=
                    '<tr>
                        <td class="text-left">' . $package->name . '</td>
                        <td class="text-right">
                            <span class="order">' . ($index + 1) . '</span>
                            <input type="hidden" name="package_id[]" value="' . $package->id .'">
                            <input type="hidden" name="package_order[]" class="package-order" value="' . ($index + 1) .'">
                        </td>
                    </tr>';
            }
        $response .=
                '</tbody>
            </table>';

        return $response;
    }

    public function getSelected()
    {
        $packages = Package::whereIn('id', request('packages'))->with('course', 'level', 'subject', 'chapter', 'language');

        $package = $packages->get()->last();
        $price = $packages->sum('price');
        $discountedPrice = $packages->sum('discounted_price');
        $specialPrice = $packages->sum('special_price');

        return ['package' => $package ?? null, 'price' => $price ?? null, 'discounted_price' => $discountedPrice ?? null, 'special_price' => $specialPrice ?? null];
    }

    public function syncPackages()
    {
        $packages = Package::query();

        $packages = $packages->get();

        foreach ($packages as $package) {
            $packageIDs = [];

            if ($package->type == 2) {
                $packageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');
            } else {
                $packageIDs[] = $package->id;
            }

            $packageVideos = PackageVideo::whereIn('package_id', $packageIDs)->with('video')->get();

            $totalDuration = $packageVideos->sum('video.duration') ?? null;
            $totalVideos = $packageVideos->count() ?? null;

            $package->total_duration = $totalDuration;
            $package->total_videos = $totalVideos;
            $package->save();
        }

        return 'Package successfully synced.';
    }

    public function getProfessors()
    {
        $package = request()->input('package');

        $package = Package::find($package);

        if (! $package) {
            return response()->json(['message' => 'Package does not exist'], 404);
        }

        $packageIDs = [];

        if ($package->type == Package::TYPE_SUBJECT_LEVEL) {
            $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');

            foreach ($chapterPackageIDs as $chapterPackageID) {
                $packageIDs[] = $chapterPackageID;
            }
        } else {
            $packageIDs[] = $package->id;
        }

        $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id')->unique();

        $professors = Professor::whereIn('id', $professorIDs)->get();

        return '<pre>' . json_encode($professors, JSON_PRETTY_PRINT) . '</pre>';
    }

    public function getCSV()
    {
        $hasStudyMaterials = request()->input('has_study_materials');

        if ($hasStudyMaterials != ('true' || 'false')) {
            return 'Please enter a valid input';
        }

        return Excel::download(new PackagesExport($hasStudyMaterials), 'PACKAGES_' . time() . '.csv');
    }

    public function verify()
    {
        $packageIDs = request()->input('packages');

        $chapterPackageCount = Package::query()
            ->whereIn('id', $packageIDs)
            ->where('type', Package::TYPE_CHAPTER_LEVEL)
            ->count();

        $subjectPackageCount = Package::query()
            ->whereIn('id', $packageIDs)
            ->where('type', Package::TYPE_SUBJECT_LEVEL)
            ->count();

        $isSortable = false;

        if ($chapterPackageCount > 0 && $subjectPackageCount == 0) {
            $isSortable = true;
        }

        if ($chapterPackageCount == 0 && $subjectPackageCount > 0) {
            $isSortable = true;
        }

        return response()->json(['is_sortable' => $isSortable]);
    }
}
