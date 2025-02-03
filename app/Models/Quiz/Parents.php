<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Parents extends Model
{
    use SoftDeletes;

    protected $table = 'parent';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

}
