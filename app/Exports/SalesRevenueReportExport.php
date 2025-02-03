<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\SubjectPackage;
use App\Models\Video;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesRevenueReportExport implements FromQuery, WithMapping, WithHeadings
{
    private $search;
    private $fromDate;
    private $toDate;
    private $status;

    public function __construct($search = null, $fromDate = null, $toDate = null, $status = null)
    {
        $this->search = $search;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query()
    {
//        $orderItems = OrderItem::query()->with('package.course', 'package.level')->whereHas('package', function ($query){
//            $query->where('course_id', 7);
//        });

        $orderItems = OrderItem::query();

        $orderItems->with('order.student');
        if(empty($this->search) && empty($this->fromDate) && empty($this->toDate) && empty($this->status)){
          
            $year = date('Y');
            $month = date('m');
            $startOfMonth = Carbon::create($year, $month)->startOfMonth();
            $orderItems->whereDate('created_at', '>=', $startOfMonth);
        }

        if(empty($this->fromDate) && empty($this->toDate) && !empty($this->status)){
          
            $year = date('Y');
            $month = date('m');
            $startOfMonth = Carbon::create($year, $month)->startOfMonth();
            $orderItems->whereDate('created_at', '>=', $startOfMonth);
        }


        if ($this->search) {
            $search = '%' . $this->search . '%';

            $orderItems->where(function ($query) use ($search) {
                $query->whereHas('order', function ($query) use ($search) {
                    $query->where('id', $search)
                        ->orWhere('transaction_id', $search)
                        ->orWhere('net_amount', $search);
                })->orWhere(function ($query) use ($search) {
                    $query->whereHas('order.student', function ($query) use ($search) {
                        $query->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search)
                            ->orWhere('phone', 'like', $search);
                    });
                });
            });
        }

        if ($this->fromDate && $this->toDate) {
            $orderItems->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        }

        if ($this->status) {
            $orderItems->whereHas('order', function ($query) {
                $query->where('payment_status', $this->status);
            });
        }

        return $orderItems;
    }

    /**
     * @param OrderItem $orderItem
     * @return array|void
     */
    public function map($orderItem): array
    {
        return [
            [
                $orderItem->order->id ?? null,
                $orderItem->order->student->name ?? null,
                $orderItem->order->student->email ?? null,
                $orderItem->order->student->phone ?? null,
                $this->getPaymentStatus($orderItem),
                $orderItem->order->net_amount ?? null,
//                $orderItem->price ?? null,
//                $orderItem->package->price ?? null,
                $orderItem->created_at->format('d/m/Y H:i') ?? null,
                $this->getPackage($orderItem) ?? null,
//                $orderItem->package->course->name ?? null,
//                $orderItem->package->level->name ?? null,
                $this->getProfessors($orderItem) ?? null,
                $this->getProfessors($orderItem, $count = true) ?? null,
                $this->getPrice($orderItem) ?? null,
                $this->getIsRefund($orderItem)
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'ORDER ID',
            'NAME',
            'EMAIL',
            'PHONE',
            'STATUS',
            'TOTAL AMOUNT',
//            'AMOUNT PAID',
//            'COST',
            'DATE',
            'PACKAGE',
//            'COURSE',
//            'LEVEL',
            'PROFESSORS',
            'SINGLE/MULTIPLE PROFESSORS',
            'PRICE',
            'REFUNDED?'
        ];
    }
    public function getIsRefund($orderItem)
    {
        $is_refund = '';

        if ($orderItem->order) {
            if ($orderItem->order->is_refunded==1)
            {
                $is_refund='Yes';
            }
            else
            {
                $is_refund= 'No';
            }
        }

        return $is_refund;
    }
    public function getProfessors($orderItem, $count = false)
    {
        $package = Package::find($orderItem->package_id);

        if (!$package) {
            return null;
        }

        $packageIDs = [];

        if ($package->type == 2) {
            $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->pluck('chapter_package_id');

            foreach ($chapterPackageIDs as $chapterPackageID) {
                $packageIDs[] = $chapterPackageID;
            }
        } else {
            $packageIDs[] = $package->id;
        }

        $videoIDs = PackageVideo::whereIn('package_id', $packageIDs)->pluck('video_id');
        $professorIDs = Video::whereIn('id', $videoIDs)->pluck('professor_id');
        $professors = Professor::whereIn('id', $professorIDs)->pluck('name');

        if ($count) {
            return count($professors) > 1 ? 'Multiple' : 'Single';
        }

        return implode("\n", $professors->toArray());
    }

    public function getPrice($orderItem)
    {
        if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
            return $orderItem->booking_amount;
        }

        return $orderItem->price;
    }

    public function getPackage($orderItem)
    {
        if ($orderItem->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
            if ($orderItem->package) {
                return $orderItem->package->name . ' (Study Material)';
            }
        }

        return $orderItem->package->name ?? null;
    }
    public function getPaymentStatus($orderItem)
    {
        $status = null;

        if ($orderItem->order) {
            if ($orderItem->order->payment_status==1)
            {
                $status=Order::PAYMENT_STATUS_SUCCESS_TEXT;
            }
            elseif ($orderItem->order->payment_status==2)
            {
                $status=Order::PAYMENT_STATUS_FAILED_TEXT;
            }
            elseif ($orderItem->order->payment_status==3)
            {
                $status=Order::PAYMENT_STATUS_RETURN_TEXT;
            }
            elseif ($orderItem->order->payment_status==4)
            {
                $status=Order::PAYMENT_STATUS_FAILED_TEXT;
            }
            elseif ($orderItem->order->payment_status==5)
            {
                $status=Order::PAYMENT_STATUS_INITIATED_TEXT;
            }
        }

        return $status;

    }
}
