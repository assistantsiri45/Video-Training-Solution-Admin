<?php

namespace App\Exports;

use App\Models\CustomizedPackage;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\SubjectPackage;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VideoExport implements FromQuery, WithMapping, WithHeadings
{
    private $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Video::query();

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where('title', 'like', $search);
        }

        return $query;
    }

    /**
     * @param Video $video
     * @return array|void
     */
    public function map($video): array
    {
        return [
            [
                $video->title,
                $video->description,
                $video->formatted_duration,
                $this->getPackages($video->id),
                $video->created_at->toDayDateTimeString()
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'TITLE',
            'DESCRIPTION',
            'DURATION',
            'PACKAGES',
            'CREATED AT'
        ];
    }

    public function getPackages($videoID)
    {
        $chapterPackageIDs = PackageVideo::where('video_id', $videoID)
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

        return implode("\n", $packages->toArray());
    }
}
