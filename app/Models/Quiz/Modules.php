<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Modules extends Model
{
    use SoftDeletes;

    protected $table = 'module_mst';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getTests()
    {
        return $this->belongsToMany('App\Models\Quiz\Test', 'test_modules', 'module_id', 'test_id');
    }

    public function getConcept(){
        return $this->belongsTo('App\Models\Quiz\Concept','concept_id','id');
    }

    public function getChapter(){
        return $this->belongsTo('App\Models\Quiz\Chapters','chapter_id','id');
    }

    public function getSubject(){
        return $this->belongsTo('App\Models\Quiz\Subjects','subject_id','id');
    }

}
