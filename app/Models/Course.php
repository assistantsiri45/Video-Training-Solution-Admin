<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = [
        'has_package'
    ];

    public function levels() {
        return $this->hasMany(Level::class);
    }

    public function students() {
        return $this->hasMany(Student::class);
    }

    public function scopeSearch($query, $searchText)
    {
        return $query->where('name', 'LIKE', '%'.$searchText.'%');
    }
    public function getHasPackageAttribute()
    {
        $package_withCourse = Package::where('course_id',$this->id)->get();
            if(count($package_withCourse)){
                return true;
            }
            else{
                
                return false;
            }
    }
}
