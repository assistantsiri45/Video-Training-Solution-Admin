<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class SchoolManagement extends Model
{
    use SoftDeletes;

    protected $table = 'school_management';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

}
