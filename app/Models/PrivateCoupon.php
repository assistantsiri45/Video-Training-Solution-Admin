<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PrivateCoupon extends Pivot
{

    protected $table = 'private_coupons';

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }


     /******Added BY TE *******/

     public function package_type(){
        return $this->belongsTo(PackageType::class);
    }

    /********TE Ends*******/
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
}
