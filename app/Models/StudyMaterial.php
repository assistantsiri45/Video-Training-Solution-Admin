<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class StudyMaterial extends Model
{
    use SoftDeletes;

    protected $appends = [
        'file'
    ];
    const STUDY_MATERIALS = 1;
    const STUDY_MATERIALS_TEXT = 'STUDY MATERIAL';
    const STUDY_PLAN = 2;
    const STUDY_PLAN_TEXT = 'STUDY PLAN';
    const TEST_PAPER = 3;
    const TEST_PAPER_TEXT = 'TEST PAPER';

    public function getFileAttribute() {
        if ($this->file_name) {
            return  env('IMAGE_URL').'/study_materials/'.$this->file_name;
        }

        return null;

    }

    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function level() {
        return $this->belongsTo(Level::class);
    }
    public function language() {
        return $this->belongsTo(Language::class);
    }
    public function professor() {
        return $this->belongsTo(Professor::class);
    }
}
