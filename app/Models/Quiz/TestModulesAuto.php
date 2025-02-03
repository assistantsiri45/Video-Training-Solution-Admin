<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class TestModulesAuto extends Model
{
    use SoftDeletes;

    protected $table = 'test_module_selection';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];


}
