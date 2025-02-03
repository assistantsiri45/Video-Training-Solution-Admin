<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CseetStudentDoc extends Model
{
    // public function user()
    // {
    //     return $this->belongsTo(User::class,'user_id','id');
    // }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function package() {
        return $this->belongsTo(Package::class);
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
}
