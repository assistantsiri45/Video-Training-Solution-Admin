<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JMoney extends Model
{

    use SoftDeletes;

    const SIGN_UP = 1;
    const FIRST_PURCHASE = 2;
    const PROMOTIONAL_ACTIVITY = 3;
    const REFERRAL_ACTIVITY = 4;
    const REFUND = 5;
    const CASHBACK = 6;
    const PURCHASE= 8;

    public function students()
    {
        return $this->belongsTo(Student::class,'user_id','user_id');
    }

}
