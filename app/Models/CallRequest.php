<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallRequest extends Model
{
    use SoftDeletes;

//    public function getCreatedAtAttribute($value)
//    {
//        $carbon = new Carbon($value);
//        return $carbon->format('m/d/Y h:m:s');
//    }
//
//    public function getUpdatedAtAttribute($value)
//    {
//        $carbon = new Carbon($value);
//        return $carbon->format('m/d/Y h:m:s');
//    }
}
