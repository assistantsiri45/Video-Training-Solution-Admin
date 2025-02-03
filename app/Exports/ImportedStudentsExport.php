<?php

namespace App\Exports;

use App\Models\OrderItem;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImportedStudentsExport implements FromQuery, WithMapping, WithHeadings
{
    private $export_date;
    private $search;

    public function __construct($export_date = null, $search = null)
    {
        $this->export_date = $export_date;
        $this->search = $search;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Student::query()->with('user','orderItems.package')->whereHas('user',function ($query)
        {
            $query->where('is_imported',1);
        });

        if ($this->export_date || $this->search) {
            $query->whereDate('created_at', $this->export_date)
                  ->orWhere('name', $this->search);
        }

        return $query;
    }

    /**
     * @param Student $student
     * @return array|void
     */
    public function map($student): array
    {
        return [
            [
                $student->name,
                $student->email,
                $student->phone,
                $this->getPackage($student) ?? null,
                $student->created_at
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'PHONE',
            'PACKAGE',
            'CREATED AT'
        ];
    }

    public function getPackage($student)
    {
        $packages = [];

        $orderItems = OrderItem::with('package')->where('user_id', $student->user_id)->get();

        foreach ($orderItems as $orderItem) {

            $packages[] =$orderItem->package->name;
        }

        $packages = collect($packages)->all();

        $packages = implode(', ', $packages);

        return $packages;
    }
}
