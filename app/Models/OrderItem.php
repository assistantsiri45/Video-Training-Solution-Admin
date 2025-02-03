<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{

    const PRICE = 1;
    const DISCOUNTED_PRICE = 2;
    const SPECIAL_PRICE = 3;
    const PEN_DRIVE = 4;
    const PEN_DRIVE_DISCOUNTED_PRICE = 5;
    const PEN_DRIVE_SPECIAL_PRICE = 6;

    const PRICE_TEXT = 'ACTUAL PRICE';
    const DISCOUNTED_PRICE_TEXT = 'DISCOUNTED PRICE';
    const SPECIAL_PRICE_TEXT = 'SPECIAL PRICE';
    const PEN_DRIVE_TEXT = 'PEN DRIVE';
    const PEN_DRIVE_DISCOUNTED_PRICE_TEXT = 'PEN DRIVE DISCOUNTED PRICE';
    const PEN_DRIVE_SPECIAL_PRICE_TEXT = 'PEN DRIVE SPECIAL PRICE';

    const ONLINE = 1;
    const PENDRIVE = 2;
    const ONLINE_TEXT = 'ONLINE';
    const PENDRIVE_TEXT = 'PENDRIVE';

    const G_DRIVE = 3;
    const G_DRIVE_TEXT = 'G-DRIVE';
    
    const PAYMENT_STATUS_FAILED = 0;
    const PAYMENT_STATUS_PARTIALLY_PAID = 1;
    const PAYMENT_STATUS_FULLY_PAID = 2;

    const ITEM_TYPE_PACKAGE = 1;
    const ITEM_TYPE_STUDY_MATERIAL = 2;

    const STATUS_ORDER_PLACED = 1;
    const STATUS_ORDER_ACCEPTED = 2;
    const STATUS_ORDER_SHIPPED = 3;
    const STATUS_ORDER_DELIVERED = 4;
    const STATUS_ORDER_CANCELED = 5;

    const STATUS_ORDER_PLACED_TEXT = 'Order Received';
   // const STATUS_ORDER_PLACED_TEXT = 'Order Placed';
    const STATUS_ORDER_ACCEPTED_TEXT = 'Order Accepted';
    const STATUS_ORDER_SHIPPED_TEXT = 'Order Shipped';
    const STATUS_ORDER_DELIVERED_TEXT = 'Order Delivered';
    const STATUS_ORDER_CANCELED_TEXT = 'Order Canceled';

    const REVIEW_STATUS_ACCEPTED = 'Accepted';
    const REVIEW_STATUS_PENDING = 'Pending';
    const REVIEW_STATUS_REJECTED = 'Rejected';


    protected $casts = [
        'price' => 'double',
        'is_prebook' => 'boolean',
        'is_booking_amount_paid' => 'boolean',
        'is_balance_amount_paid' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function studyMaterialOrderLog()
    {
        return $this->hasOne(StudyMaterialOrderLog::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'order_id', 'order_id');
    }
//by jeswill
    public function courierOrder()
    {
        return $this->hasOne(Courierorder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

//end by jeswill
//    public function packages() {
//        return $this->belongsTo(Package::class);
//    }

}
