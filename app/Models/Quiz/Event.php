<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'event';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getBoard(){
        return $this->belongsTo('App\Models\Quiz\Board','board_id','id');
    }

    public function getGrade(){
        return $this->belongsTo('App\Models\Quiz\Grade','grade_id','id');
    }

    public function getSubject(){
        return $this->belongsTo('App\Models\Quiz\Subject','subject_id','id');
    }

    public function getTests()
    {
        return $this->belongsToMany('App\Models\Quiz\Test', 'event_rounds', 'event_id', 'test_id')->withPivot('id', 'test_id','start_datetime', 'end_datetime',  'order_by', 'status');
    }

    public function getTest($id)
    {
        return $this->belongsToMany('App\Models\Quiz\Test', 'event_rounds', 'event_id', 'test_id')->withPivot('id', 'test_id','start_datetime', 'end_datetime',  'order_by', 'status')->wherePivot('id', $id)->first()->toArray();
    }

    public function getEventRounds()
    {
        return $this->hasMany('App\Models\Quiz\EventDetails', 'event_id', 'id');
    }
    public function getEventRound(){
        return $this->hasOne('App\Models\Quiz\EventDetails','event_id','id');
    }

    public function getInstruction(){
        return $this->belongsTo('App\Models\Quiz\Instruction','event_details','id');
    }
}
