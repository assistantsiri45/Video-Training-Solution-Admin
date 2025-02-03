<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromQuery, WithMapping, WithHeadings
{
    private $signUpDate;

    public function __construct($signUpDate = null)
    {
        $this->signUpDate = $signUpDate;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Student::query();

        if ($this->signUpDate) {
            $query->whereDate('created_at', $this->signUpDate);
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
                $student->course->name ?? '-',
                $student->level->name ?? '-',
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
            'COURSE',
            'LEVEL',
            'CREATED AT'
        ];
    }
}
