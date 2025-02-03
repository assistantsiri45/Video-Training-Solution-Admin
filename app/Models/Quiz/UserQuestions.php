<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class UserQuestions extends Model
{
    use SoftDeletes;

    protected $table = 'user_questions';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getQuestion(){
        return $this->belongsTo('App\Models\Quiz\Question','question_id','id');
    }

    public function getUser(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function getUserTest(){
        return $this->belongsTo('App\Models\Quiz\UserTests','user_test_id','id');
    }

    public function getUserAnswer(){
        return $this->hasOne('App\Models\Quiz\UserAnswers','user_question_id','id');
    }
}

