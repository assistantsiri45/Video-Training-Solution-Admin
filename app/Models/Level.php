<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = [
        'has_package'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function subjects() {
        return $this->hasMany(Subject::class);
    }

    public function scopeOfCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeSearch($query, $searchText)
    {
        return $query->where('name', 'LIKE', '%'.$searchText.'%');
    }
    
    public function getHasPackageAttribute()
    {
        $package_withLevel = Package::where('level_id',$this->id)->get();
            if(count($package_withLevel)){
                return true;
            }
            else{
                
                return false;
            }
    }
}
