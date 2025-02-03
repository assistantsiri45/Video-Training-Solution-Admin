<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class School extends Model
{
    use SoftDeletes;

    protected $table = 'school';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

}
