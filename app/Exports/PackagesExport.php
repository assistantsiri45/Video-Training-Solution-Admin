<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class PackagesExport implements FromQuery, WithMapping, WithHeadings
{
    private $hasStudyMaterial;

    public function __construct($hasStudyMaterial)
    {
        if ($hasStudyMaterial == 'true') {
            $this->hasStudyMaterial = true;
        } else {
            $this->hasStudyMaterial = false;
        }
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Package::query();

        $query->ofPublished();

        if ($this->hasStudyMaterial) {
            $query->whereHas('studyMaterials');
        } else {
            $query->whereDoesntHave('studyMaterials');
        }

        return $query;
    }

    /**
     * @param Package $packages
     * @return array|void
     */
    public function map($packages): array
    {
        if ($this->hasStudyMaterial) {

            $title = implode("\n", $packages->studyMaterials->pluck('title')->toArray());
            $filename = implode("\n", $packages->studyMaterials->pluck('file_name')->toArray());

            return [
                [
                    $packages->id,
                    $packages->name,
                    $title,
                    $filename
                ],
            ];
        } else {
            return [
                [
                    $packages->id,
                    $packages->name
                ],
            ];
        }
    }

    public function headings(): array
    {
        if ($this->hasStudyMaterial) {
            return [
                '#',
                'Name',
                'Study Materials',
                'File Name'
            ];
        } else {
            return [
                '#',
                'Name'
            ];
        }
    }
}
