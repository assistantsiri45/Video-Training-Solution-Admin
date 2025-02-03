<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackageProfessorRevenueExport implements FromQuery, WithMapping, WithHeadings
{
//    private $search;


    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Package::query()->with('course', 'level', 'subject','language', 'chapter', 'user')
            ->where('is_approved',1);

//        if ($this->search) {
//            $search = '%' . $this->search . '%';
//            $query->whereHas('student', function($query) use ($search) {
//                $query->where('name', 'like', $search);
//            });
//        }

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
                $package->name,
                $this->getType($package),
                $this->getCategoryName($package),
                $package->course->name,
                $package->level->name,
                $package->subject->name ?? null,
                $package->language->name,
                $package->price,
                $this->getProfessors($package),
                $this->getTotalNumberOfProfessors($package),
                $package->professor_revenue,
                $package->updated_at,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Type',
            'Category',
            'Course',
            'Level',
            'Subject',
            'Language',
            'Selling Cost',
            'Professors',
            'Number Of professors',
            'Professor Revenue',
            'Last Published Date',
        ];
    }

    public function getType($package)
    {
        if ($package->type == 1) {
            $type = 'TYPE_CHAPTER_LEVEL';
        }
        else if($package->type == 2) {
            $type = 'TYPE_SUBJECT_LEVEL';
        }
        else{
            $type = 'TYPE_CUSTOMIZED';
        }
        return $type;
    }

    public function getCategoryName($package)
    {
        if ($package->is_mini) {
            return 'Mini Package';
        }

        if ($package->is_crash_course) {
            return 'Crash Course';
        }

        return 'Full Package';
    }

    public function getProfessors($package)
    {
        $professors = [];
        foreach ($package->professors as $data)
        {
            $professors[] = $data->name;
        }
        $professors = collect($professors)->all();
        return implode(', ', $professors);
    }

    public function getTotalNumberOfProfessors($package)
    {
        return count($package->professors);
    }
}
