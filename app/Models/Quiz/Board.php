<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Board extends Model
{
    use  SoftDeletes;

    protected $table = 'courses';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getGrades(){
        return $this->hasMany('App\Models\Quiz\Grade','course_id','id');
    }

    public function getBoardId($name)
    {
        $res = Board::where('courses.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public  static function getAllBoard($id = "")
    {
        $query = Board::where('status','1');
        if($id != '')
        {
            $query->where('id',$id);
        }    
        $result = $query->get();
        return $result;
    }
    // public function get_grade_id()
    // {
    //    return $this->hasOne('App\User');
    // }
}
