<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Courier extends Model
{
    public function courierOrder()
    {
        return $this->hasOne(Courierorder::class);
    }
}
