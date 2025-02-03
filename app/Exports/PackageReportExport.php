<?php

namespace App\Exports;

use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackageReportExport implements FromQuery, WithMapping, WithHeadings
{
    private $sales;
    private $amount;
    private $rating;
    private $professor;

    public function __construct($sales, $amount, $rating, $professor)
    {
        $this->sales = $sales;
        $this->amount = $amount;
        $this->rating = $rating;
        $this->professor = $professor;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Package::query();

        if ($this->sales) {
            $query->has('orderItems', $this->sales);
        }

        if ($this->amount) {
            $query->where('price', '>=', $this->amount);
        }

        if ($this->rating) {
            $query->wherehas('chapter.video.professor', function ($query) {
                $query->where('rating', $this->rating);
            });
        }

        if ($this->professor) {
            $query->wherehas('chapter.video.professor', function ($query) {
                $query->where('id', $this->professor);
            });
        }

        return $query;
    }

    /**
     * @param Package $package
     * @return array|void
     */
    public function map($package): array
    {
        return [
            [
                $package->id,
                $package->created_at->toDayDateTimeString()
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'CREATED AT'
        ];
    }
}
