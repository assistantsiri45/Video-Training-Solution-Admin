<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class UserTests extends Model
{
    use SoftDeletes;

    protected $table = 'user_test';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getTest(){
        return $this->belongsTo('App\Models\Quiz\Test','test_id','id');
    }

    public function getUser(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function getUserQuestions(){
        return $this->hasMany('App\Models\Quiz\UserQuestions','user_test_id','id');
    }
}
