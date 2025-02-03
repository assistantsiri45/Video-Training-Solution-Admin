<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Answer extends Model
{
    use SoftDeletes;

    protected $table = 'question_answers';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];



}
