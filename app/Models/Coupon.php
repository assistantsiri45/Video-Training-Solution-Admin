<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    const PUBLIC = 1;
    const PRIVATE = 2;
    const FLAT = 1;
    const FLAT_TEXT = "FLAT";
    const PERCENTAGE = 2;
    const PERCENTAGE_TEXT = "PERCENTAGE";
    const FIXED_PRICE = 3;
    const FIXED_PRICE_TEXT = 'FIXED PRICE';
    const DRAFT = 1;
    const PUBLISH = 2;
    const UNPUBLISH = 3;
    const SUCCESS = 1;
    const SUCCESS_TEXT = "SUCCESS";
    const FAILED = 2;
    const FAILED_TEXT = "FAILED";
    const RETURNED = 3;
    const RETURNED_TEXT = "RETURNED";



    public function getValidFromAttribute($value)
    {
        $carbon = new Carbon($value);
        return $carbon->format('Y-m-d');
    }

    public function getValidToAttribute($value)
    {
        $carbon = new Carbon($value);
        return $carbon->format('Y-m-d');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'private_coupon')->using(PrivateCoupon::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
