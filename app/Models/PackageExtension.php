<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageExtension extends Model
{
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
