<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeResource extends Model
{
    use SoftDeletes;

    const YOUTUBE_ID = 1;
    const YOUTUBE_ID_TEXT = "YOUTUBE";
    const IMAGE = 2;
    const IMAGE_TEXT = "IMAGE";
    const NOTES = 3;
    const NOTES_TEXT = "DOCUMENT";
    const AUDIO_FILES = 4;
    const AUDIO_FILES_TEXT = "AUDIO";
    const JW_VIDEO = 5;
    const JW_VIDEO_TEXT = "JW VIDEO";


    public function getFileAttribute($value) {
        if (! $value) {
            return null;
        }

        if($this->type==2){
            return env('IMAGE_URL').'/free_resources/images/'.$value;
        }
        elseif($this->type==3||$this->type==4){
            return env('IMAGE_URL').'/free_resources/'.$value;
        }

    }

    public function getThumbnailFileUrlAttribute()
    {
        if (! $this->thumbnail_file) {
            return null;
        }

        return  env('IMAGE_URL') . '/free_resources/thumbnails/' . $this->thumbnail_file;
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    
    public function package_type(){
        return $this->belongsTo(PackageType::class);
    }
}
