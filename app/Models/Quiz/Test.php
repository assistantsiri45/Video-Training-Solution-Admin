<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Test extends Model
{
    use SoftDeletes;

    protected $table = 'test';

    protected $primaryKey = 'id';

    public function getModules()
    {
        return $this->hasMany('App\Models\Quiz\TestModules', 'test_id', 'id');
    }

    public function getInstruction()
    {
        return $this->belongsTo('App\Models\Quiz\Instruction', 'instruction_id', 'id');
    }

    public function getContent()
    {
        return $this->belongsToMany('App\Models\Quiz\ContentLibrary', 'test_content', 'test_id', 'content_id');
    }

    public function getBoard(){
        return $this->belongsTo('App\Models\Quiz\Board','board_id','id');
    }

//    public function getModule($id)
//    {
//        return $this->belongsToMany('App\Models\Quiz\Modules', 'test_modules', 'test_id', 'module_id')->withPivot('id', 'break_time', 'order_by', 'status')->wherePivot('id', $id)->first()->toArray();
//    }
//
//    public function getTestModule($id)
//    {
//        return $this->belongsToMany('App\Models\Quiz\Modules', 'test_modules', 'test_id', 'module_id')->withPivot('id', 'break_time', 'order_by', 'status')->wherePivot('id', $id)->first()->toArray();
//    }

    public static function test_data($grade=null,$board=null)
    {
        $query = Test::where('is_publish','1');
        // $query = Test::select('*');
                    $query->where('test_type', 'Practice');
                    $query->where('ques_selection_type', 'manual');
                    if($grade != null)
                    {
                        $query->where('grade_id',$grade);
                    }
                    if($board != null)
                    {
                        $query->where('board_id',$board);
                    }
                    $test = $query->get();
        return $test;

           }
}
