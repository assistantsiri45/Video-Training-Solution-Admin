<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class TestModuleQuestions extends Model
{
    use SoftDeletes;

    protected $table = 'test_module_ques';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];


    public function getQuestion(){
        return $this->belongsTo('App\Models\Quiz\Question','question_id','id');
    }


}
