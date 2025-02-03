<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageVideo extends Model
{
    protected $guarded = ['id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
