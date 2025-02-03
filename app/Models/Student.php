<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    use Notifiable;

    protected $guarded = ['id'];

    public function getCreatedAtAttribute($value)
    {
        $carbon = new Carbon($value);
        return $carbon->format('Y-m-d');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'user_id','user_id');
    }

    public function jmoney()
    {
        return $this->hasMany(JMoney::class,'user_id','user_id');
    }

    public function professors() {
        return $this->belongsTo(Professor::class)
                    ->using(Testimonial::class);
    }

    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function coupons() {
        return $this->belongsToMany(Coupon::class, 'private_coupons','student_id','coupon_id');
    }

    public function scopeOfCoupon($query, $couponId)
    {
        if ($couponId) {
            return $query->where('coupon_id', $couponId);
        }

        return $query;
    }

}
