<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Taxonomy extends Model
{
    use SoftDeletes;

    protected $table = 'taxonomy_mst';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

}
