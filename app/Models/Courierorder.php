<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Courierorder extends Model
{
    public function courier()
    {
        return $this->belongsTo(Courier::class,'courier_id','id');
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class,'order_item_id','id');
    }
}
