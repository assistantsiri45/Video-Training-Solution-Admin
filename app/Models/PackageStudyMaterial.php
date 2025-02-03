<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageStudyMaterial extends Model
{
    protected $fillable = [
        'package_id', 'study_material_id'
    ];
    public function package() {
        return $this->belongsTo(Package::class);
    }
    public function study_material() {
        return $this->belongsTo(StudyMaterialV1::class);
    }
}
