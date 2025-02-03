<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class TestModules extends Model
{
    use SoftDeletes;

    protected $table = 'test_modules';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getQuestions(){
        return $this->hasMany('App\Models\Quiz\TestModuleQuestions','module_id','id');
    }

    public function getSelections(){
        return $this->hasMany('App\Models\Quiz\TestModulesAuto','module_id','id');
    }

    public function getTest(){
        return $this->belongsTo('App\Models\Quiz\Test','test_id','id');
    }
}
