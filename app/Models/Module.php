<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = [
        'has_package'
    ];


    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }
     /******Added BY TE  *****/

     public function package_type(){
        return $this->belongsTo(PackageType::class);
    }   

    /**************TE Ends**************** */
    public function getHasPackageAttribute()
    {
        $package_withModule = PackageVideo::where('module_id',$this->id)->get();
            if(count($package_withModule)){
                return true;
            }
            else{
                
                return false;
            }
    }
}
