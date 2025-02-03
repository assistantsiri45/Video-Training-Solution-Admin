<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Subjects extends Model
{
    use SoftDeletes;

    protected $table = 'subjects';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getGrade(){
        return $this->belongsTo('App\Models\Quiz\Grade','level_id','id');
    }

    public function getChapters(){
        return $this->hasMany('App\Models\Quiz\Chapters','subject_id','id');
    }

    public function getSubjectId($name)
    {
        $res = Subjects::where('name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public static function getAllSubject()
    {
        return Subjects::select('*')->get();
    }

    public function getSubjects($grade_id)
    {
        $res = Subjects::where('subjects.level_id',$grade_id)->get();

        foreach ($res as $key => $value) {
            $response[$value->id] = $value->name;
        }
        return $response;
    }

    
}
