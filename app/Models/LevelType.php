<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelType extends Model
{
    public function course() {
        return $this->belongsTo(Course::class);
    }
    
    public function level() {
        return $this->belongsTo(Level::class);
    }
    public function packagetype() {
        return $this->HasOne(PackageType::class,'id','package_type_id');
    }
}
