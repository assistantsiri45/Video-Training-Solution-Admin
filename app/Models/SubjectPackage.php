<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectPackage extends Model
{
    protected $guarded = ['id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function chapterPackage()
    {
        return $this->belongsTo(Package::class, 'chapter_package_id');
    }

}
