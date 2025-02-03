<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyMaterialV1 extends Model
{
    use SoftDeletes;

    protected $table = 'study_materials_v1';

    protected $appends = [
        'file',
        'has_package'
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
    public function packages() {
        return $this->belongsToMany(Package::class, 'package_study_materials','study_material_id','package_id');
    }
    public function getHasPackageAttribute()
    {
        $package_withMaterial = PackageStudyMaterial::where('study_material_id',$this->id)->get();
            if(count($package_withMaterial)){
                return true;
            }
            else{
                
                return false;
            }
    }

    public function user()
    {
        return $this->belongsTo(User::class,'added_by');
    }

      /******Added BY TE  *****/

      public function package_type(){
        return $this->belongsTo(PackageType::class);
    }   

    /**************TE Ends**************** */

}
