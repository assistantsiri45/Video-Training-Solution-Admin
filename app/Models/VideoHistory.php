<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoHistory extends Model
{
    //
    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

}
