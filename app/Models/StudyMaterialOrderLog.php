<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterialOrderLog extends Model
{
    protected $guarded = ['id'];

    const STATUS_ORDER_PLACED = 1;
    const STATUS_ORDER_ACCEPTED = 2;
    const STATUS_ORDER_SHIPPED = 3;
    const STATUS_ORDER_DELIVERED = 4;

    const STATUS_ORDER_PLACED_TEXT = 'Order Received';
  //  const STATUS_ORDER_PLACED_TEXT = 'Order Placed';
    const STATUS_ORDER_ACCEPTED_TEXT = 'Order Accepted';
    const STATUS_ORDER_SHIPPED_TEXT = 'Order Shipped';
    const STATUS_ORDER_DELIVERED_TEXT = 'Order Delivered';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }


}
