<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;

class UserAnswers extends Model
{

    protected $table = 'users_answers';

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

    public function getAnswer(){
        return $this->belongsTo('App\Models\Quiz\Answer','option_id','id');
    }


}
