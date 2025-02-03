<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cart extends Model
{
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
