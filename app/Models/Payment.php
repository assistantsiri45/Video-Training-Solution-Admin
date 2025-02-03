<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const PAYMENT_STATUS_SUCCESS = 1;
    const PAYMENT_STATUS_FAILURE = 0;

    const UPDATE_METHOD_CCAVENUE = 1;
    const UPDATE_METHOD_MANUAL = 2;
    const UPDATE_METHOD_CRON = 3;
    const UPDATE_METHOD_EASEBUZZ = 4;
    const STATE_ID_MAHARASHTRA = 22;

    public function user()
    {
        return $this->belongsTo(User::class,'payment_updated_by','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function getReceiptNo(){

        $total_success_transactions = Payment::where('payment_status',Order::PAYMENT_STATUS_SUCCESS)->max('id');

        return $total_success_transactions + 1;

    }
    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'payment_order_items')->withPivot('is_balance_payment');
    }
}
