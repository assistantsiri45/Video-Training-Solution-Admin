<?php

namespace App\Exports;

use App\Models\CallRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallRequestExport implements FromQuery, WithMapping, WithHeadings
{
    private $status;
    private $search;
    private $createdAt;

    public function __construct($status = null, $search = null, $createdAt = null)
    {
        $this->status = $status;
        $this->search = $search;
        $this->createdAt = $createdAt;
    }

    /**
    * @return Builder
    */
    public function query()
    {
        $query = CallRequest::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where('phone', 'like', $search);
        }

        if ($this->createdAt) {
            $query->whereDate('created_at', $this->createdAt);
        }

        return $query;
    }

    /**
     * @param CallRequest $callRequests
     * @return array|void
     */
    public function map($callRequests): array
    {
        return [
            [
                $callRequests->id,
                $callRequests->phone,
                $callRequests->status == 1 ? 'New' : 'Updated',
                $callRequests->created_at->toFormattedDateString()
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'PHONE',
            'STATUS',
            'CREATED AT'
        ];
    }
}
