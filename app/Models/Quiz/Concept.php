<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Concept extends Model
{
    use SoftDeletes;

    protected $table = 'concept_mst';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getConceptId($name)
    {
        $res = Concept::where('concept_mst.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public function getChapter(){
        return $this->belongsTo('App\Models\Quiz\Chapter','chapter_id','id');
    }

    public function getModules(){
        return $this->hasMany('App\Models\Quiz\Modules','concept_id','id');
    }

    public function getConceptArray(){
        return Concept::hasMany('App\Models\Quiz\chapter','chapter_id','id');
    }

}
