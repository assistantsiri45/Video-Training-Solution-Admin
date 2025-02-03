<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderRevenueExport implements FromQuery, WithMapping, WithHeadings
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
        $query = Order::PaymentStatus()->select('orders.*')->with('student', 'associate.user','third_party.user','orderItems','payment');

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->whereHas('student', function($query) use ($search) {
                $query->where('name', 'like', $search)
                ->orWhere('email','LIKE', $search)
                ->orWhere('phone','LIKE', $search);
            });
        }
        if ($this->search) {
            $query->Orwhere('transaction_id',  '=', $this->search);
        }
        if ($this->search) {
            $query->Orwhere('net_amount','LIKE', '%'.$this->search.'%');
        }
        if ($this->search) {
            $query->Orwhere('payment_status','LIKE', '%'.$this->search.'%');
        }
        if ($this->search) {
            $query->Orwhere('orders.id','=', $this->search);
        }
        

        return $query;
    }

    /**
     * @param Order $order
     * @return array|void
     */
    public function map($order): array
    {

        return [
            [
                $order->id,
                $this->getStudentName($order),
                $this->getPackage($order) ?? null,
                $order->net_amount,
                $order->transaction_id,
                $this->getPaymentStatus($order),
                $this->getIsRefund($order),
                $order->associate->user->name ?? null,
                $order->created_at->toDayDateTimeString()
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'STUDENT',
            'PACKAGES',
            'NET AMOUNT',
            'TRANSACTION ID',
            'PAYMENT STATUS',
            'REFUND?',
            'ASSOCIATE',
            'CREATED AT'
        ];
    }
    public function getStudentName($order){

        if(!empty($order->student->name)){
            $student = $order->student->name;
        }else{
            $student = '-';
        }
        return $student;
    }

    public function getIsRefund($order)
    {
        if ($order->is_refunded==1)
        {
            $is_refund='Yes';
        }
        else
        {
            $is_refund= 'No';
        }
        return $is_refund;
    }
    public function getPaymentStatus($order)
    {
        if ($order->payment_status==1)
        {
            $status=Order::PAYMENT_STATUS_SUCCESS_TEXT;
        }
        elseif ($order->payment_status==2)
        {
            $status=Order::PAYMENT_STATUS_FAILED_TEXT;
        }
        elseif ($order->payment_status==3)
        {
            $status=Order::PAYMENT_STATUS_RETURN_TEXT;
        }
        elseif ($order->payment_status==4)
        {
            $status=Order::PAYMENT_STATUS_FAILED_TEXT;
        }
        elseif ($order->payment_status==5)
        {
            $status=Order::PAYMENT_STATUS_INITIATED_TEXT;
        }
        return $status;

    }
    public function getPackage($order){
        $package = '';
        $i = 1;
        foreach($order->orderItems as $val2){
                           
            if(!empty($val2->package)){
                $package.=$i.') ';
                $package.=$val2->package->name .PHP_EOL;
            }else{
                $package.='-'.PHP_EOL;
            }
                ++$i;
            }

        return $package;
    }
}
