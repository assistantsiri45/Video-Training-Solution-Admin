<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class ContentLibrary extends Model
{
    use SoftDeletes;

    protected $table = 'content_library';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getInstructionId($name)
    {
        $res = ContentLibrary::where('content_library.name',$name)->get();
        if(!empty($res)){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public function getContentConcept(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Concept','concept_id','id');
    }
    public function getChapter(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Chapter','chapter_id','id');
    }
    public function getSubject(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Subject','subject_id','id');
    }
    public function getTaxonomy(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Taxonomy','taxonomy_id','id');
    }
    public function getLearning(){
        return ContentLibrary::belongsTo('App\Models\Quiz\LearningStage','learning_stage_id','id');
    }
    public function getConcept(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Concept','concept_id','id');
    }
    public function getBoard(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Board','board_id','id');
    }
    public function getGrade(){
        return ContentLibrary::belongsTo('App\Models\Quiz\Grade','grade_id','id');
    }

}
