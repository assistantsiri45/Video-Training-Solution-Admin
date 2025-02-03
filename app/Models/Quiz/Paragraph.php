<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Paragraph extends Model
{
    use SoftDeletes;

    protected $table = 'paragraph';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getQuestions(){
        return $this->hasMany('App\Models\Quiz\Question','paragraph_id','id');
    }

    public function getParagraphId($name)
    {
        $res = Paragraph::where('paragraph.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

}
