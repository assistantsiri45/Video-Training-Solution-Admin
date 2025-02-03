<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentExport implements FromQuery, WithMapping, WithHeadings
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
        $query = Payment::query()->with('order')->where('payment_status', Payment::PAYMENT_STATUS_SUCCESS);

        if ($this->search) {
            $search = '%' . $this->search . '%';

            $query->whereHas('student', function ($query) use ($search) {
                $query->where('name', 'like', $search);
            })
                ->orWhere('transaction_id', 'like', $search)
                ->orWhere('receipt_no', 'like', $search);
        }

        return $query;
    }

    /**
     * @param Payment $payment
     * @return array|void
     */
    public function map($payment): array
    {
        return [
            [
                $payment->student->name ?? '-',
                $payment->receipt_no,
                $payment->transaction_id,
                $payment->net_amount,
                $this->getGST($payment),
                $payment->payment_updated_method == Payment::UPDATE_METHOD_EASEBUZZ ? 'EASEBUZZ' : 'CCAVENUE',
                $payment->payment_status == Payment::PAYMENT_STATUS_SUCCESS ? 'Success' : 'Failed',
                $this->getRefund($payment),
                $payment->created_at->toDayDateTimeString()
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'RECEIPT NO.',
            'TRANSACTION ID',
            'NET AMOUNT IN RUPEES',
            'GST IN RUPEES',
            'PAYMENT GATEWAY',
            'STATUS',
            'REFUNDED?',
            'CREATED AT'
        ];
    }

    public function getGST($query)
    {
        if ($query->cgst_amount && $query->sgst_amount) {
            return  round($query->cgst_amount) . ' ( ' . $query->cgst . '% ) ' . ' + ' . round($query->sgst_amount) . ' ( ' . $query->sgst . '% ) ';
        }

        if ($query->igst) {
            return  round($query->igst_amount) . ' ( ' . $query->igst . '% ) ';
        }
    }
    public function getRefund($payment)
    {
        if (isset($payment->order) && $payment->order->is_refunded==1)
        {
            $is_refund='Yes';
        }
        else
        {
            $is_refund= 'No';
        }
        return $is_refund;
    }
}
