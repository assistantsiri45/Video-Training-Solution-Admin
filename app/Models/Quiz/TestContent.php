<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;

class TestContent extends Model
{

    protected $table = 'test_content';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getTest(){
        return $this->belongsTo('App\Models\Test','test_id','id');
    }

    public function getContent(){
        return $this->belongsTo('App\Models\ContentLibrary','content_id','id');
    }

}
