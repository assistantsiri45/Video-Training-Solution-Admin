<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Model
{
    use SoftDeletes;

    protected $table = 'levels';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getBoard(){
        return $this->belongsTo('App\Models\Quiz\Board','course_id','id');
    }

    public function gradeDetails($board_id){
    	return Grade::where('course_id',$board_id)->get();
    }

    public function getSubjects(){
        return $this->hasMany('App\Models\Quiz\Subject','level_id','id');
    }

    public function getGradeId($name)
    {
        $res = Grade::where('levels.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }
    public static function get_all_grade($id = "")
    {
        $query = Grade::select('*');
                if($id != '')
                {
                    $query->where('id',$id);
                }
        return   $query->get();
     
    }

    public function getGrades($board_id)
    {
        $res = Grade::where('levels.course_id',$board_id)->get();
        // dd($res);
        foreach ($res as $key => $value) {
            $response[$value->id] = $value->name;
        }
        // dd($response);
        return $response;
    }
}
