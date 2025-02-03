<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    protected $appends = [
        'has_package'
    ];
    public function getHasPackageAttribute()
    {
        $package_withType = Package::where('package_type',$this->id)->get();
            if(count($package_withType)){
                return true;
            }
            else{
                
                return false;
            }
    }
    public function level_type()
    {
        return $this->HasMany(LevelType::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
}
