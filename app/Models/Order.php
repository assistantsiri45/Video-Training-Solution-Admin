<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use SoftDeletes;
    const STATUS_RECEIVED = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_SHIPPED = 3;
    const STATUS_DELIVERED = 4;
    const STATUS_PENDING = 5;

    const PAYMENT_STATUS_SUCCESS = 1;
    const PAYMENT_STATUS_FAILED = 2;
    const PAYMENT_STATUS_RETURN = 3;
    const PAYMENT_STATUS_ABORTED = 4;
    const PAYMENT_STATUS_INITIATED = 5;

    const PAYMENT_STATUS_SUCCESS_TEXT = 'SUCCESS';
    const PAYMENT_STATUS_FAILED_TEXT = 'FAILED';
    const PAYMENT_STATUS_RETURN_TEXT = 'RETURN';
    const PAYMENT_STATUS_ABORTED_TEXT = 'ABORTED';
    const PAYMENT_STATUS_INITIATED_TEXT = 'PAYMENT INITIATED';

    const PAYMENT_MODE_ONLINE = 1;
    const PAYMENT_MODE_COD = 2;
    const PAYMENT_MODE_PREPAID = 3;

    const UPDATE_METHOD_CCAVENUE = 1;
    const UPDATE_METHOD_MANUAL = 2;
    const UPDATE_METHOD_CRON = 3;
    const UPDATE_METHOD_EASEBUZZ = 4;

    protected $guarded = ['id'];


    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'user_id','user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function associate()
    {
        return $this->belongsTo(Associate::class, 'associate_id', 'user_id');
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function third_party()
    {
        return $this->belongsTo(ThirdPartyAgent::class, 'third_party_id', 'user_id');
    }

    public function scopePaymentStatus($query)
    {
        return $query->where('orders.transaction_response_status',Order::PAYMENT_STATUS_SUCCESS_TEXT)->where('orders.payment_status', Order::PAYMENT_STATUS_SUCCESS);
    }
    public function holidayoffer()
    {
        return $this->belongsTo(HolidayOffer::class,'holiday_offer_id','id');
    }

}
