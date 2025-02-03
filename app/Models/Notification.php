<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];

    const ALL_USER_TYPE = 1;
    const PACKAGE_TYPE = 2;
    const LEVEL_TYPE = 3;
    const SINGLE_USER_TYPE = 4;
    const COURSE_TYPE = 5;

    static $types = [
        1 => 'All USER',
        2 => 'PACKAGE',
        3 => 'LEVEL',
        4 => 'SINGLE USER',
        5=>'COURSE',
    ];

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
