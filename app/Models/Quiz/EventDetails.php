<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class EventDetails extends Model
{
    use SoftDeletes;

    protected $table = 'event_rounds';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getGrades(){
        return $this->hasMany('App\Models\Quiz\Grade','board_id','id');
    }

    public function getTest(){
        return $this->belongsTo('App\Models\Quiz\Test','test_id','id');
    }
}
