<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Chapter extends Model
{
    use SoftDeletes;

    protected $table = 'chapters';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getSubject(){
        return $this->belongsTo('App\Models\Quiz\Subjects','subject_id','id');
    }

    public function getConcepts(){
        return $this->hasMany('App\Models\Quiz\Concept','chapter_id','id');
    }

    public function getChapterId($name)
    {
        $res = Chapter::where('name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public function getChapters($subject_id)
    {
        $res = Chapter::where('chapters.subject_id',$subject_id)->get();

        foreach ($res as $key => $value) {
            $response[$value->id] = $value->name;
        }
        return $response;
    }

}
