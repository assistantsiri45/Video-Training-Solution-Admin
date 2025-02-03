<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentOrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use App\Services\ProfessorRevenueService;

class PaymentController extends Controller
{
    /** @var ProfessorRevenueService $professorRevenueService */
    var $professorRevenueService;

    /**
     * PaymentController constructor.
     * @param ProfessorRevenueService $professorRevenueService
     */
    public function __construct(ProfessorRevenueService $professorRevenueService)
    {
        $this->professorRevenueService = $professorRevenueService;
    }

    public function syncPayments()
    {
        return '0';

        $orders = Order::query();
        $orders = $orders->get();

        foreach ($orders as $order) {
            $payment = new Payment;
            $payment->user_id = $order->user_id;
            $payment->order_id = $order->id;
            $payment->cgst = $order->cgst;
            $payment->cgst_amount = $order->cgst_amount;
            $payment->sgst = $order->sgst;
            $payment->sgst_amount = $order->sgst_amount;
            $payment->igst = $order->igst;
            $payment->igst_amount = $order->igst_amount;
            $payment->transaction_id = $order->transaction_id;
            $payment->transaction_response = $order->transaction_response;
            $payment->transaction_response_status = $order->transaction_response_status;

            if ($order->payment_status == 1) {
                $payment->payment_status = 1;
            } else {
                $payment->payment_status = 0;
            }

            $payment->net_amount = $order->net_amount;
            $payment->payment_updated_by = $order->payment_updated_by;
            $payment->payment_updated_method = $order->payment_updated_method;
            $payment->updated_ip_address = $order->updated_ip_address;
            $payment->save();
        }

        return '1';
    }

    public function syncPaymentOrderItems()
    {
        return '0';

        $payments = Payment::query();
        $payments = $payments->get();

        foreach ($payments as $payment) {
            $orderItems = OrderItem::where('order_id', $payment->order_id)->get();

            foreach ($orderItems as $orderItem) {
                $paymentOrderItem = new PaymentOrderItem;
                $paymentOrderItem->payment_id = $payment->id;
                $paymentOrderItem->order_item_id = $orderItem->id;
                $paymentOrderItem->save();
            }
        }

        return '1';
    }

    public function syncOrderItems()
    {
        return '0';

        $orderItems = OrderItem::query();
        $orderItems = $orderItems->get();

        foreach ($orderItems as $orderItem) {
            $orderItem->user_id = $orderItem->order->user_id;

            if ($orderItem->order->payment_status == 1) {
                $orderItem->payment_status = 2;
                $orderItem->save();
            }
        }

        return '1';
    }

    public function syncPaymentReceipt()
    {
        return '0';

        $payments = Payment::query();
        $payments = $payments->get();

        $i = 1;

        foreach ($payments as $payment) {
            if ($payment->payment_status == 1) {
                $payment->receipt_no = $i;
                $payment->save();

                $i++;
            }
        }

        return '1';
    }

    public function syncPaymentCCAvenueOrderID()
    {
        return '0';

        $payments = Payment::query();
        $payments = $payments->get();

        foreach ($payments as $payment) {
            $payment->cc_avenue_order_id = $payment->order_id ?? null;
            $payment->save();
        }

        return '1';
    }

    public function syncOrderItemsUserID()
    {
        return '0';

        $orderItems = OrderItem::query();
        $orderItems = $orderItems->get();

        foreach ($orderItems as $orderItem) {
            $orderItem->user_id = $orderItem->order->user_id ?? null;
            $orderItem->save();
        }


        return '1';
    }

    public function syncPrepaidOrders()
    {
        return '0';

        $orders = Order::query();

        $orders->where('payment_mode', 3);
        $orders->with('payment');

        $orders = $orders->get();

        foreach ($orders as $order) {
            $address = Address::where('user_id', $order->user_id)->first();
            $order->address_id = $address->id;
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->pin = $address->pin;
            $order->address = $address->address;
            $order->updated_by = Auth::id();
            $order->updated_method = 2;
            $order->updated_ip_address = request()->ip();
            $order->save();

            if ($order->state) {
                $CGST = Setting::where('key', 'cgst')->first()->value ?? null;
                $SGST = Setting::where('key', 'sgst')->first()->value ?? null;
                $IGST = Setting::where('key', 'igst')->first()->value ?? null;

                $amountExceptCGST_SGST = ($order->net_amount * 100) / (100 + $CGST + $SGST);
                $amountExceptIGST = ($order->net_amount * 100) / (100 + $IGST);

                $CGSTAmount = (($amountExceptCGST_SGST * $CGST) / 100);
                $SGSTAmount = (($amountExceptCGST_SGST * $SGST) / 100);
                $IGSTAmount = (($amountExceptIGST * $IGST) / 100);


                if (strtoupper($order->state) == 'MAHARASHTRA') {
                    $order->cgst_amount = $CGSTAmount;
                    $order->sgst_amount = $SGSTAmount;
                    $order->cgst = $CGST;
                    $order->sgst = $SGST;

                    if ($order->payment) {
                        $order->payment->sgst_amount = $SGSTAmount;
                        $order->payment->sgst_amount = $SGSTAmount;
                        $order->payment->sgst = $CGST;
                        $order->payment->sgst = $SGST;
                    }
                } else {
                    $order->igst_amount = $IGSTAmount;
                    $order->igst = $IGST;

                    if ($order->payment) {
                        $order->payment->igst_amount = $IGSTAmount;
                        $order->payment->igst = $IGST;
                    }
                }

                $order->save();

                if ($order->payment) {
                    $order->payment->save();
                }
            }
        }

        return '1';
    }

    public function syncOrderItemsType()
    {
        return '0';

        $orderItems = OrderItem::query()->where('item_type', null)->get();

        foreach ($orderItems as $orderItem) {
            $orderItem->item_type = 1;
            $orderItem->item_id = $orderItem->package_id;
            $orderItem->save();
        }

        return '1';
    }

    public function syncProfessorRevenues()
    {
//        return '0';

        $orderItems = OrderItem::query()
            ->where('item_type', 1)
            ->whereIn('payment_status', [1, 2])
            ->with('payment')
            ->get();

        foreach ($orderItems as $orderItem) {
            if ($orderItem->payment_status == 1 || $orderItem->payment_status == 2) {
                try {
                    $netAmount = null;

                    if (! $orderItem->is_prebook) {
                        $netAmount = $orderItem->price;
                    }

//                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
//                        $netAmount = $orderItem->booking_amount;
//                    }

                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                        $netAmount = $orderItem->balance_amount + $orderItem->booking_amount;
                    }

                    $this->professorRevenueService->store([
                        'package_id' => $orderItem->package_id,
                        'net_amount' => $netAmount,
                        'invoice_id' => $orderItem->payment->receipt_no ?? null,
                        'invoice_date' => $orderItem->payment->created_at ?? null,
                        'order_id' => $orderItem->order_id,
                        'order_item_id' => $orderItem->id,
                    ]);
                } catch (Exception $exception) {
                    info ('PROFESSOR REVENUE SERVICE EXCEPTION: ' . $exception->getMessage());
                }
            }
        }

        return '1';
    }

    public function syncPaymentsCreatedAt()
    {
//        return '0';

        $payments = Payment::query()
            ->whereDate('created_at', '2020-10-09')
            ->where('payment_updated_method', null)
            ->with('order')
            ->get();

        foreach ($payments as $payment) {
            $payment->created_at = $payment->order->created_at;
            $payment->updated_at = $payment->order->created_at;
            $payment->save();
        }

        return '1';
    }
}
