<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CAFinalPackagesExport implements FromQuery, WithMapping, WithHeadings
{

    public function __construct()
    {

    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Package::query()->with('packageVideos.video.professor')->where('level_id', 6);

        info($query->get());

        return $query;
    }

    /**
     * @param Package $packages
     * @return array|void
     */
    public function map($packages): array
    {
        $professors = implode(", ", $packages->professors->pluck('name')->toArray());

        return [
            [
                $packages->id,
                $packages->name,
                $professors
            ],
        ];

    }
    public function headings(): array
    {
            return [
                '#',
                'Name',
                'Professors'
            ];
    }
}
